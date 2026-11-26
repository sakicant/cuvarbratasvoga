<?php
/*
 * ČBS admin — pregled prijava.
 * Admin države vidi samo prijave iz svoje države. Glavni admin (bez države) vidi sve i upravlja adminima.
 */
declare(strict_types=1);
require __DIR__ . '/../api/lib.php';

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('Cache-Control: no-store');
header("Content-Security-Policy: default-src 'self'; style-src 'self' https://fonts.googleapis.com 'unsafe-inline'; font-src https://fonts.gstatic.com; img-src 'self' data:; script-src 'self' 'unsafe-inline'; frame-ancestors 'none'");

start_session();
$pdo = db();
$a = (string)($_GET['a'] ?? 'list');
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';

function flash(string $msg, string $type = 'ok'): void { $_SESSION['flash'] = [$type, $msg]; }
function go(string $qs = ''): void { header('Location: ./' . ($qs !== '' ? '?' . $qs : '')); exit; }
function random_password(): string
{
    $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $p = '';
    for ($i = 0; $i < 12; $i++) $p .= $chars[random_int(0, strlen($chars) - 1)];
    return $p;
}
function event_start_for(int $year): string
{
    $ev = cfg('event');
    return (int)$ev['year'] === $year ? $ev['start_date'] : "$year-06-15";
}
function country_name(?string $c): string { return $c ? (CBS_COUNTRIES[$c] ?? $c) : 'Sve države'; }

/* ---------- izgled ---------- */
function page_start(string $title, ?array $me = null): void
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    ?><!doctype html>
<html lang="hr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= h($title) ?> · ČBS admin</title>
<link rel="icon" href="../assets/brand/favicon/favicon.ico">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head><body>
<header class="top">
  <a class="brand" href="./"><img src="../assets/brand/png/cbs-logo-white-256.png" alt=""><span>ČBS admin</span></a>
  <?php if ($me): ?>
  <nav>
    <a href="./">Prijave</a>
    <?php if (is_super($me)): ?><a href="?a=users">Admini</a><?php endif; ?>
    <a href="?a=password">Lozinka</a>
    <form method="post" action="?a=logout"><?= csrf_field() ?><button class="link">Odjava</button></form>
  </nav>
  <span class="who"><?= h($me['name']) ?> · <?= h(country_name($me['country'])) ?></span>
  <?php endif; ?>
</header>
<main class="wrap">
<?php if ($flash): ?><div class="flash <?= h($flash[0]) ?>"><?= $flash[1] /* već escapirano */ ?></div><?php endif;
}
function page_end(): void { echo "</main></body></html>"; exit; }

/* ---------- prvo postavljanje: glavni admin ---------- */
$adminCount = (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
if ($adminCount === 0) {
    $err = '';
    if ($isPost) {
        csrf_check();
        $key = (string)($_POST['setup_key'] ?? '');
        $u = clean($_POST['username'] ?? '', 40);
        $n = clean($_POST['name'] ?? '', 80);
        $e = clean($_POST['email'] ?? '', 160);
        $p = (string)($_POST['password'] ?? '');
        if (too_many('setup', 10, 900)) $err = 'Previše pokušaja. Pričekaj 15 minuta.';
        elseif (!hash_equals((string)cfg('setup_key'), $key) || str_starts_with((string)cfg('setup_key'), 'PROMIJENI')) { record_attempt('setup'); $err = 'Neispravan ključ za postavljanje (setup_key u api/config.php).'; }
        elseif (!preg_match('/^[a-z0-9._-]{3,40}$/i', $u)) $err = 'Korisničko ime: 3–40 znakova, slova, brojke, točka, crtica.';
        elseif (mb_strlen($n) < 2) $err = 'Upiši ime.';
        elseif (strlen($p) < 10) $err = 'Lozinka mora imati barem 10 znakova.';
        else {
            $pdo->prepare('INSERT INTO admins (username, name, email, country, password_hash, created_at) VALUES (?, ?, ?, NULL, ?, ?)')
                ->execute([$u, $n, $e ?: null, password_hash($p, PASSWORD_DEFAULT), now()]);
            flash('Glavni admin je kreiran. Prijavi se.');
            go();
        }
    }
    page_start('Postavljanje');
    ?>
    <div class="card narrow">
      <h1>Prvo postavljanje</h1>
      <p class="muted">Kreiraj glavni admin račun. On vidi sve države i dodaje admine za pojedine države.</p>
      <?php if ($err): ?><div class="flash err"><?= h($err) ?></div><?php endif; ?>
      <form method="post" class="stack">
        <?= csrf_field() ?>
        <label>Ključ za postavljanje<input name="setup_key" type="password" required autocomplete="off"></label>
        <label>Korisničko ime<input name="username" required autocomplete="username" value="<?= h($_POST['username'] ?? '') ?>"></label>
        <label>Ime i prezime<input name="name" required value="<?= h($_POST['name'] ?? '') ?>"></label>
        <label>E-mail (neobavezno)<input name="email" type="email" value="<?= h($_POST['email'] ?? '') ?>"></label>
        <label>Lozinka (min. 10 znakova)<input name="password" type="password" required minlength="10" autocomplete="new-password"></label>
        <button class="btn">Kreiraj glavnog admina</button>
      </form>
    </div>
    <?php
    page_end();
}

/* ---------- prijava / odjava ---------- */
if ($a === 'login' && $isPost) {
    csrf_check();
    if (too_many('login', 8, 900)) {
        flash('Previše neuspjelih pokušaja. Pričekaj 15 minuta.', 'err');
        go();
    }
    $s = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
    $s->execute([clean($_POST['username'] ?? '', 40)]);
    $row = $s->fetch();
    $hash = $row['password_hash'] ?? password_hash('dummy-' . random_int(0, 1 << 30), PASSWORD_DEFAULT); // jednako vrijeme provjere
    if (password_verify((string)($_POST['password'] ?? ''), $hash) && $row) {
        session_regenerate_id(true);
        $_SESSION['aid'] = (int)$row['id'];
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
        clear_attempts('login');
        if (password_needs_rehash($row['password_hash'], PASSWORD_DEFAULT)) {
            $pdo->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')->execute([password_hash((string)$_POST['password'], PASSWORD_DEFAULT), $row['id']]);
        }
        $pdo->prepare('UPDATE admins SET last_login = ? WHERE id = ?')->execute([now(), $row['id']]);
        go();
    }
    record_attempt('login');
    flash('Pogrešno korisničko ime ili lozinka.', 'err');
    go();
}

$me = current_admin();
if (!$me) {
    page_start('Prijava');
    ?>
    <div class="card narrow">
      <h1>Prijava</h1>
      <form method="post" action="?a=login" class="stack">
        <?= csrf_field() ?>
        <label>Korisničko ime<input name="username" required autocomplete="username" autofocus></label>
        <label>Lozinka<input name="password" type="password" required autocomplete="current-password"></label>
        <button class="btn">Prijavi se</button>
      </form>
    </div>
    <?php
    page_end();
}

if ($a === 'logout' && $isPost) {
    csrf_check();
    $_SESSION = [];
    session_regenerate_id(true);
    go();
}

$super = is_super($me);

/* dohvati prijavu uz provjeru prava (admin države ne može dohvatiti tuđu) */
function load_reg(array $me, int $id): ?array
{
    [$w, $p] = scope($me);
    $s = db()->prepare("SELECT * FROM registrations WHERE id = ? AND $w");
    $s->execute(array_merge([$id], $p));
    return $s->fetch() ?: null;
}

/* ---------- promjena statusa / bilješke ---------- */
if (($a === 'status' || $a === 'save') && $isPost) {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $r = load_reg($me, $id);
    if (!$r) { flash('Prijava nije pronađena.', 'err'); go(); }
    $status = (string)($_POST['status'] ?? $r['status']);
    if (!isset(CBS_STATUS[$status])) $status = $r['status'];
    $paidAt = $status === 'paid' ? ($r['paid_at'] ?: now()) : null;
    $note = $a === 'save' ? mb_substr(trim((string)($_POST['admin_note'] ?? '')), 0, 2000) : $r['admin_note'];
    db()->prepare('UPDATE registrations SET status = ?, paid_at = ?, admin_note = ?, updated_at = ?, updated_by = ? WHERE id = ?')
        ->execute([$status, $paidAt, $note ?: null, now(), $me['username'], $id]);
    flash(h($r['first_name'] . ' ' . $r['last_name']) . ': ' . h(CBS_STATUS[$status]) . '.');
    $back = (string)($_POST['back'] ?? '');
    go(preg_match('/^[\w=&%.+-]*$/', $back) ? $back : '');
}

if ($a === 'delete' && $isPost) {
    csrf_check();
    $r = load_reg($me, (int)($_POST['id'] ?? 0));
    if ($r) {
        db()->prepare('DELETE FROM registrations WHERE id = ?')->execute([$r['id']]);
        flash('Prijava ' . h($r['ref']) . ' je trajno obrisana.');
    }
    go();
}

/* ---------- filteri (lista i izvoz) ---------- */
$curYear = (int)cfg('event')['year'];
$year = (int)($_GET['year'] ?? $curYear);
$fCountry = $super ? (string)($_GET['c'] ?? '') : $me['country'];
if ($fCountry !== '' && !isset(CBS_COUNTRIES[$fCountry])) $fCountry = '';
$fStatus = (string)($_GET['status'] ?? '');
if ($fStatus !== '' && !isset(CBS_STATUS[$fStatus])) $fStatus = '';
$q = clean($_GET['q'] ?? '', 80);

function build_where(array $me, int $year, string $country, string $status, string $q, bool $withSearch = true): array
{
    [$w, $p] = scope($me);
    $where = ["year = ?", $w];
    $params = array_merge([$year], $p);
    if ($country !== '') { $where[] = 'country = ?'; $params[] = $country; }
    if ($withSearch && $status !== '') { $where[] = 'status = ?'; $params[] = $status; }
    if ($withSearch && $q !== '') {
        $full = is_sqlite() ? "first_name || ' ' || last_name" : "CONCAT(first_name, ' ', last_name)";
        $where[] = "(LOWER($full) LIKE ? OR LOWER(email) LIKE ? OR phone LIKE ? OR LOWER(city) LIKE ? OR LOWER(ref) LIKE ? OR LOWER(COALESCE(church,'')) LIKE ?)";
        $like = '%' . mb_strtolower($q) . '%';
        array_push($params, $like, $like, $like, $like, $like, $like);
    }
    return [implode(' AND ', $where), $params];
}

/* ---------- izvoz u Excel (CSV) ---------- */
if ($a === 'export') {
    [$w, $p] = build_where($me, $year, $fCountry, $fStatus, $q);
    $s = db()->prepare("SELECT * FROM registrations WHERE $w ORDER BY country, last_name, first_name");
    $s->execute($p);
    $fn = 'CBS' . $year . '-prijave' . ($fCountry ? '-' . $fCountry : '') . '-' . date('Ymd') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $fn . '"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    $cols = ['Broj prijave', 'Ime', 'Prezime', 'E-mail', 'Mobitel', 'Datum rođenja', 'Dob', 'Država', 'Grad', 'Adresa', 'Crkva', 'Smještaj', 'Majica', 'Posebne potrebe', 'Status', 'Uplaćeno', 'Bilješka', 'Prijavljen', 'Jezik'];
    fputcsv($out, $cols, ';');
    $start = event_start_for($year);
    while ($r = $s->fetch()) {
        $row = [$r['ref'], $r['first_name'], $r['last_name'], $r['email'], $r['phone'], date('d.m.Y', strtotime($r['birth_date'])), age_at($r['birth_date'], $start),
            country_name($r['country']), $r['city'], $r['address'], $r['church'], CBS_ACCOM[$r['accommodation']] ?? $r['accommodation'], $r['tshirt'], $r['notes'],
            CBS_STATUS[$r['status']] ?? $r['status'], $r['paid_at'] ? date('d.m.Y', strtotime($r['paid_at'])) : '', $r['admin_note'], date('d.m.Y H:i', strtotime($r['created_at'])), strtoupper($r['lang'] ?? 'hr')];
        // zaštita od formula u Excelu
        $row = array_map(fn($v) => is_string($v) && preg_match('/^[=+\-@\t\r]/', $v) ? "'" . $v : $v, $row);
        fputcsv($out, $row, ';');
    }
    fclose($out);
    exit;
}

/* ---------- promjena vlastite lozinke ---------- */
if ($a === 'password') {
    if ($isPost) {
        csrf_check();
        $cur = (string)($_POST['current'] ?? '');
        $new = (string)($_POST['new'] ?? '');
        if (!password_verify($cur, $me['password_hash'])) flash('Trenutna lozinka nije točna.', 'err');
        elseif (strlen($new) < 10) flash('Nova lozinka mora imati barem 10 znakova.', 'err');
        elseif ($new !== (string)($_POST['new2'] ?? '')) flash('Nove lozinke se ne podudaraju.', 'err');
        else {
            db()->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')->execute([password_hash($new, PASSWORD_DEFAULT), $me['id']]);
            flash('Lozinka je promijenjena.');
            go();
        }
        go('a=password');
    }
    page_start('Lozinka', $me);
    ?>
    <div class="card narrow">
      <h1>Promijeni lozinku</h1>
      <form method="post" class="stack">
        <?= csrf_field() ?>
        <label>Trenutna lozinka<input name="current" type="password" required autocomplete="current-password"></label>
        <label>Nova lozinka (min. 10 znakova)<input name="new" type="password" required minlength="10" autocomplete="new-password"></label>
        <label>Ponovi novu lozinku<input name="new2" type="password" required minlength="10" autocomplete="new-password"></label>
        <button class="btn">Spremi</button>
      </form>
    </div>
    <?php
    page_end();
}

/* ---------- upravljanje adminima (samo glavni admin) ---------- */
if ($a === 'users') {
    if (!$super) go();
    if ($isPost) {
        csrf_check();
        $do = (string)($_POST['do'] ?? '');
        if ($do === 'create') {
            $u = clean($_POST['username'] ?? '', 40);
            $n = clean($_POST['name'] ?? '', 80);
            $e = clean($_POST['email'] ?? '', 160);
            $c = (string)($_POST['country'] ?? '');
            $exists = db()->prepare('SELECT COUNT(*) FROM admins WHERE username = ?');
            $exists->execute([$u]);
            if (!preg_match('/^[a-z0-9._-]{3,40}$/i', $u)) flash('Korisničko ime: 3–40 znakova (slova, brojke, točka, crtica).', 'err');
            elseif ((int)$exists->fetchColumn() > 0) flash('To korisničko ime već postoji.', 'err');
            elseif (mb_strlen($n) < 2) flash('Upiši ime admina.', 'err');
            elseif ($e !== '' && !filter_var($e, FILTER_VALIDATE_EMAIL)) flash('E-mail nije ispravan.', 'err');
            elseif ($c !== '' && !isset(CBS_COUNTRIES[$c])) flash('Odaberi državu.', 'err');
            else {
                $pw = random_password();
                db()->prepare('INSERT INTO admins (username, name, email, country, password_hash, created_at) VALUES (?, ?, ?, ?, ?, ?)')
                    ->execute([$u, $n, $e ?: null, $c ?: null, password_hash($pw, PASSWORD_DEFAULT), now()]);
                flash('Admin <b>' . h($u) . '</b> (' . h(country_name($c ?: null)) . ') je kreiran. Privremena lozinka: <code>' . h($pw) . '</code><br>Pošalji mu je sigurnim putem – vidiš je samo sada. Neka je promijeni nakon prve prijave.');
            }
        } elseif ($do === 'reset' || $do === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $s = db()->prepare('SELECT * FROM admins WHERE id = ?');
            $s->execute([$id]);
            $t = $s->fetch();
            if (!$t) flash('Admin nije pronađen.', 'err');
            elseif ((int)$t['id'] === (int)$me['id']) flash('Ne možeš to napraviti sa svojim računom.', 'err');
            elseif ($do === 'reset') {
                $pw = random_password();
                db()->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')->execute([password_hash($pw, PASSWORD_DEFAULT), $id]);
                flash('Nova lozinka za <b>' . h($t['username']) . '</b>: <code>' . h($pw) . '</code> – vidiš je samo sada.');
            } else {
                db()->prepare('DELETE FROM admins WHERE id = ?')->execute([$id]);
                flash('Admin ' . h($t['username']) . ' je obrisan.');
            }
        }
        go('a=users');
    }
    $admins = db()->query('SELECT * FROM admins ORDER BY country IS NOT NULL, country, username')->fetchAll();
    page_start('Admini', $me);
    ?>
    <h1>Admini</h1>
    <p class="muted">Admin države vidi i uređuje samo prijave iz svoje države. Glavni admin vidi sve.</p>
    <div class="table-wrap card flush">
      <table>
        <thead><tr><th>Korisničko ime</th><th>Ime</th><th>Država</th><th>E-mail (obavijesti)</th><th>Zadnja prijava</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($admins as $ad): ?>
          <tr>
            <td><b><?= h($ad['username']) ?></b></td>
            <td><?= h($ad['name']) ?></td>
            <td><?= is_super($ad) ? '<span class="badge gold">Glavni admin</span>' : h(country_name($ad['country'])) ?></td>
            <td><?= h($ad['email'] ?? '–') ?></td>
            <td><?= $ad['last_login'] ? h(date('d.m.Y H:i', strtotime($ad['last_login']))) : '<span class="muted">nikad</span>' ?></td>
            <td class="actions">
              <?php if ((int)$ad['id'] !== (int)$me['id']): ?>
              <form method="post" onsubmit="return confirm('Generirati novu lozinku za <?= h($ad['username']) ?>?')"><?= csrf_field() ?><input type="hidden" name="do" value="reset"><input type="hidden" name="id" value="<?= (int)$ad['id'] ?>"><button class="btn small ghost">Nova lozinka</button></form>
              <form method="post" onsubmit="return confirm('Obrisati admina <?= h($ad['username']) ?>?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= (int)$ad['id'] ?>"><button class="btn small danger">Obriši</button></form>
              <?php else: ?><span class="muted">(ti)</span><?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="card" style="margin-top:24px">
      <h2>Dodaj admina</h2>
      <form method="post" class="grid4">
        <?= csrf_field() ?><input type="hidden" name="do" value="create">
        <label>Korisničko ime<input name="username" required pattern="[A-Za-z0-9._\-]{3,40}" placeholder="npr. hr.oliver"></label>
        <label>Ime i prezime<input name="name" required></label>
        <label>E-mail (za obavijesti o novim prijavama)<input name="email" type="email"></label>
        <label>Država
          <select name="country">
            <?php foreach (CBS_COUNTRIES as $k => $v): if ($k === 'XX') continue; ?><option value="<?= $k ?>"><?= h($v) ?></option><?php endforeach; ?>
            <option value="">Sve države (glavni admin)</option>
          </select></label>
        <div><button class="btn">Dodaj i generiraj lozinku</button></div>
      </form>
    </div>
    <?php
    page_end();
}

/* ---------- detalji prijave ---------- */
if ($a === 'view') {
    $r = load_reg($me, (int)($_GET['id'] ?? 0));
    if (!$r) { flash('Prijava nije pronađena.', 'err'); go(); }
    $age = age_at($r['birth_date'], event_start_for((int)$r['year']));
    page_start($r['first_name'] . ' ' . $r['last_name'], $me);
    ?>
    <p><a href="./">← Sve prijave</a></p>
    <div class="detail">
      <div class="card">
        <div class="head">
          <div><h1><?= h($r['first_name'] . ' ' . $r['last_name']) ?></h1>
          <p class="muted mono"><?= h($r['ref']) ?> · prijavljen <?= h(date('d.m.Y H:i', strtotime($r['created_at']))) ?></p></div>
          <span class="badge <?= h($r['status']) ?>"><?= h(CBS_STATUS[$r['status']] ?? $r['status']) ?></span>
        </div>
        <dl class="kv">
          <dt>E-mail</dt><dd><a href="mailto:<?= h($r['email']) ?>"><?= h($r['email']) ?></a></dd>
          <dt>Mobitel</dt><dd><a href="tel:<?= h(preg_replace('/[^\d+]/', '', $r['phone'])) ?>"><?= h($r['phone']) ?></a></dd>
          <dt>Datum rođenja</dt><dd><?= h(date('d.m.Y', strtotime($r['birth_date']))) ?> (<?= $age ?> god.<?= $age < 18 ? ' · <b>maloljetan</b>' : '' ?>)</dd>
          <dt>Država</dt><dd><?= h(country_name($r['country'])) ?></dd>
          <dt>Jezik prijave</dt><dd><?= h(strtoupper($r['lang'] ?? 'hr')) ?> <small class="muted">(na tom jeziku je poslan potvrdni e-mail)</small></dd>
          <dt>Grad</dt><dd><?= h($r['city']) ?></dd>
          <dt>Adresa</dt><dd><?= h($r['address']) ?></dd>
          <dt>Crkva / zajednica</dt><dd><?= h($r['church'] ?: '–') ?></dd>
          <dt>Smještaj</dt><dd><?= h(CBS_ACCOM[$r['accommodation']] ?? $r['accommodation']) ?></dd>
          <dt>Majica</dt><dd><?= h($r['tshirt']) ?></dd>
          <dt>Posebne potrebe</dt><dd><?= nl2br(h($r['notes'] ?: '–')) ?></dd>
          <?php if ($r['paid_at']): ?><dt>Akontacija</dt><dd>uplaćena <?= h(date('d.m.Y', strtotime($r['paid_at']))) ?></dd><?php endif; ?>
          <?php if ($r['updated_by']): ?><dt>Zadnja izmjena</dt><dd><?= h($r['updated_by']) ?>, <?= h(date('d.m.Y H:i', strtotime($r['updated_at']))) ?></dd><?php endif; ?>
        </dl>
      </div>
      <div class="card">
        <h2>Status i bilješka</h2>
        <form method="post" action="?a=save" class="stack">
          <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><input type="hidden" name="back" value="a=view&amp;id=<?= (int)$r['id'] ?>">
          <label>Status
            <select name="status"><?php foreach (CBS_STATUS as $k => $v): ?><option value="<?= $k ?>" <?= $r['status'] === $k ? 'selected' : '' ?>><?= h($v) ?></option><?php endforeach; ?></select></label>
          <label>Bilješka (vide je samo admini)<textarea name="admin_note" rows="5"><?= h($r['admin_note'] ?? '') ?></textarea></label>
          <button class="btn">Spremi</button>
        </form>
        <form method="post" action="?a=delete" class="danger-zone" onsubmit="return confirm('Trajno obrisati ovu prijavu? Ovo se ne može poništiti.')">
          <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
          <button class="btn small danger">Trajno obriši prijavu</button>
          <small class="muted">Npr. na zahtjev osobe. Za odustajanje radije koristi status „Otkazano”.</small>
        </form>
      </div>
    </div>
    <?php
    page_end();
}

/* ---------- popis prijava ---------- */
[$wAll, $pAll] = build_where($me, $year, $fCountry, '', '', false);
$st = db()->prepare("SELECT status, accommodation, tshirt, birth_date FROM registrations WHERE $wAll");
$st->execute($pAll);
$stats = ['total' => 0, 'paid' => 0, 'new' => 0, 'cancelled' => 0, 'minors' => 0, 'acc' => array_fill_keys(array_keys(CBS_ACCOM), 0), 'shirts' => array_fill_keys(CBS_SHIRTS, 0)];
$start = event_start_for($year);
foreach ($st->fetchAll() as $r) {
    $stats[$r['status']] = ($stats[$r['status']] ?? 0) + 1;
    if ($r['status'] === 'cancelled') continue;
    $stats['total']++;
    if (isset($stats['acc'][$r['accommodation']])) $stats['acc'][$r['accommodation']]++;
    if (isset($stats['shirts'][$r['tshirt']])) $stats['shirts'][$r['tshirt']]++;
    if (age_at($r['birth_date'], $start) < 18) $stats['minors']++;
}

[$w, $p] = build_where($me, $year, $fCountry, $fStatus, $q);
$ls = db()->prepare("SELECT * FROM registrations WHERE $w ORDER BY created_at DESC LIMIT 2000");
$ls->execute($p);
$rows = $ls->fetchAll();

[$sw, $sp] = scope($me);
$ys = db()->prepare("SELECT DISTINCT year FROM registrations WHERE $sw ORDER BY year DESC");
$ys->execute($sp);
$years = array_unique(array_merge([$curYear], array_map('intval', $ys->fetchAll(PDO::FETCH_COLUMN))));
rsort($years);

$qs = http_build_query(array_filter(['year' => $year, 'c' => $super ? $fCountry : '', 'status' => $fStatus, 'q' => $q], fn($v) => $v !== '' && $v !== null));

page_start('Prijave', $me);
?>
<div class="title-row">
  <h1>Prijave · ČBS <?= $year ?><?= $fCountry ? ' · ' . h(country_name($fCountry)) : ($super ? '' : '') ?></h1>
  <a class="btn ghost" href="?a=export&amp;<?= h($qs) ?>">⬇ Izvoz u Excel</a>
</div>

<div class="stats">
  <div class="stat"><b><?= $stats['total'] ?></b><span>prijavljenih</span></div>
  <div class="stat ok"><b><?= $stats['paid'] ?></b><span>akontacija uplaćena</span></div>
  <div class="stat warn"><b><?= $stats['new'] ?></b><span>čeka akontaciju</span></div>
  <div class="stat"><b><?= $stats['acc']['need_tent'] ?></b><span>treba šator</span></div>
  <div class="stat"><b><?= $stats['acc']['own_tent'] ?></b><span>vlastiti šator</span></div>
  <div class="stat"><b><?= $stats['acc']['own_arrangement'] ?></b><span>smještaj izvan kampa</span></div>
  <div class="stat"><b><?= $stats['minors'] ?></b><span>mlađih od 18</span></div>
  <div class="stat shirts"><span>Majice</span><div><?php foreach ($stats['shirts'] as $k => $v): ?><i><?= $k ?> <b><?= $v ?></b></i><?php endforeach; ?></div></div>
</div>

<form class="filters" method="get">
  <select name="year" onchange="this.form.submit()"><?php foreach ($years as $y): ?><option <?= $y === $year ? 'selected' : '' ?>><?= $y ?></option><?php endforeach; ?></select>
  <?php if ($super): ?>
  <select name="c" onchange="this.form.submit()">
    <option value="">Sve države</option>
    <?php foreach (CBS_COUNTRIES as $k => $v): ?><option value="<?= $k ?>" <?= $fCountry === $k ? 'selected' : '' ?>><?= h($v) ?></option><?php endforeach; ?>
  </select>
  <?php endif; ?>
  <select name="status" onchange="this.form.submit()">
    <option value="">Svi statusi</option>
    <?php foreach (CBS_STATUS as $k => $v): ?><option value="<?= $k ?>" <?= $fStatus === $k ? 'selected' : '' ?>><?= h($v) ?></option><?php endforeach; ?>
  </select>
  <input type="search" name="q" value="<?= h($q) ?>" placeholder="Traži ime, e-mail, grad, broj prijave…">
  <button class="btn small">Traži</button>
  <?php if ($q !== '' || $fStatus !== '' || ($super && $fCountry !== '')): ?><a href="./?year=<?= $year ?>">Poništi</a><?php endif; ?>
</form>

<?php if (!$rows): ?>
  <div class="card empty">Nema prijava<?= $q !== '' || $fStatus !== '' ? ' za ovu pretragu' : '' ?>.</div>
<?php else: ?>
<div class="table-wrap card flush">
  <table class="regs">
    <thead><tr><th>Broj</th><th>Ime i prezime</th><?php if ($super && !$fCountry): ?><th>Država</th><?php endif; ?><th>Grad</th><th>Mobitel</th><th>Dob</th><th>Smještaj</th><th>Majica</th><th>Status</th><th>Prijavljen</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): $age = age_at($r['birth_date'], $start); ?>
      <tr class="<?= h($r['status']) ?>">
        <td class="mono"><a href="?a=view&amp;id=<?= (int)$r['id'] ?>"><?= h($r['ref']) ?></a></td>
        <td><a href="?a=view&amp;id=<?= (int)$r['id'] ?>"><b><?= h($r['first_name'] . ' ' . $r['last_name']) ?></b></a><?php if ($r['notes']): ?> <span class="dot" title="<?= h($r['notes']) ?>">!</span><?php endif; ?><br><small class="muted"><?= h($r['email']) ?></small></td>
        <?php if ($super && !$fCountry): ?><td><?= h($r['country']) ?></td><?php endif; ?>
        <td><?= h($r['city']) ?></td>
        <td class="nowrap"><a href="tel:<?= h(preg_replace('/[^\d+]/', '', $r['phone'])) ?>"><?= h($r['phone']) ?></a></td>
        <td><?= $age ?><?= $age < 18 ? ' <span class="badge small">&lt;18</span>' : '' ?></td>
        <td><?= ['need_tent' => 'Treba šator', 'own_tent' => 'Vlastiti šator', 'own_arrangement' => 'Izvan kampa'][$r['accommodation']] ?? h($r['accommodation']) ?></td>
        <td><?= h($r['tshirt']) ?></td>
        <td>
          <form method="post" action="?a=status" class="inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><input type="hidden" name="back" value="<?= h($qs) ?>">
            <?php if ($r['status'] === 'new'): ?>
              <input type="hidden" name="status" value="paid"><button class="badge new as-btn" title="Klikni kad akontacija stigne">Čeka · označi uplaćeno</button>
            <?php elseif ($r['status'] === 'paid'): ?>
              <input type="hidden" name="status" value="new"><button class="badge paid as-btn" title="Klikni za poništavanje" onclick="return confirm('Vratiti na „Čeka akontaciju”?')">✓ Uplaćeno</button>
            <?php else: ?>
              <span class="badge cancelled">Otkazano</span>
            <?php endif; ?>
          </form>
        </td>
        <td class="nowrap muted"><?= h(date('d.m. H:i', strtotime($r['created_at']))) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<p class="muted"><?= count($rows) ?> prikazano.</p>
<?php endif;
page_end();
