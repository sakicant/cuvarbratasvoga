<?php
/* POST /api/register.php — prima prijavu s web stranice (JSON). */
declare(strict_types=1);
require __DIR__ . '/lib.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

function out(array $data, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    out(['open' => (bool)cfg('registration_open')]);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(['ok' => false, 'error' => 'Nedozvoljena metoda.'], 405);

// zahtjev mora doći s iste domene
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && parse_url($origin, PHP_URL_HOST) !== parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST)) {
    out(['ok' => false, 'error' => 'Nedozvoljen zahtjev.'], 403);
}

if (!cfg('registration_open')) out(['ok' => false, 'error' => 'Prijave trenutno nisu otvorene.'], 403);

$raw = file_get_contents('php://input', false, null, 0, 20000);
$in = json_decode((string)$raw, true);
if (!is_array($in)) $in = $_POST;

// zamka za botove: skriveno polje mora ostati prazno
if (!empty($in['website'])) out(['ok' => true, 'ref' => 'OK']);

try {
    if (too_many('register', 15, 3600)) {
        out(['ok' => false, 'error' => 'Previše prijava s ove adrese. Pokušaj ponovno za sat vremena ili nam piši na e-mail.'], 429);
    }

    $ev = cfg('event');
    $f = [
        'first_name'    => clean($in['first_name'] ?? '', 80),
        'last_name'     => clean($in['last_name'] ?? '', 80),
        'email'         => mb_strtolower(clean($in['email'] ?? '', 160)),
        'phone'         => clean($in['phone'] ?? '', 40),
        'birth_date'    => clean($in['birth_date'] ?? '', 10),
        'country'       => clean($in['country'] ?? '', 2),
        'city'          => clean($in['city'] ?? '', 80),
        'address'       => clean($in['address'] ?? '', 160),
        'church'        => clean($in['church'] ?? '', 120),
        'accommodation' => clean($in['accommodation'] ?? '', 20),
        'tshirt'        => clean($in['tshirt'] ?? '', 5),
        'notes'         => mb_substr(trim((string)($in['notes'] ?? '')), 0, 1000),
    ];

    $err = [];
    if (mb_strlen($f['first_name']) < 2) $err['first_name'] = 'Upiši ime.';
    if (mb_strlen($f['last_name']) < 2) $err['last_name'] = 'Upiši prezime.';
    if (!filter_var($f['email'], FILTER_VALIDATE_EMAIL)) $err['email'] = 'Upiši ispravnu e-mail adresu.';
    if (strlen(preg_replace('/\D/', '', $f['phone'])) < 6) $err['phone'] = 'Upiši broj mobitela.';
    $bd = DateTime::createFromFormat('!Y-m-d', $f['birth_date']);
    if (!$bd || $bd->format('Y-m-d') !== $f['birth_date']) {
        $err['birth_date'] = 'Upiši datum rođenja.';
    } else {
        $age = age_at($f['birth_date'], $ev['start_date']);
        if ($age < 5 || $age > 105) $err['birth_date'] = 'Provjeri datum rođenja.';
    }
    if (!isset(CBS_COUNTRIES[$f['country']])) $err['country'] = 'Odaberi državu.';
    if (mb_strlen($f['city']) < 2) $err['city'] = 'Upiši grad.';
    if (mb_strlen($f['address']) < 3) $err['address'] = 'Upiši adresu.';
    if (!isset(CBS_ACCOM[$f['accommodation']])) $err['accommodation'] = 'Odaberi smještaj.';
    if (!in_array($f['tshirt'], CBS_SHIRTS, true)) $err['tshirt'] = 'Odaberi veličinu majice.';
    if (empty($in['consent'])) $err['consent'] = 'Potrebna je tvoja privola.';

    if ($err) out(['ok' => false, 'error' => 'Provjeri označena polja.', 'fields' => $err], 422);

    $pdo = db();
    record_attempt('register');

    // ista osoba već prijavljena ove godine?
    $dup = $pdo->prepare("SELECT ref FROM registrations WHERE year = ? AND LOWER(first_name) = LOWER(?) AND LOWER(last_name) = LOWER(?) AND birth_date = ? AND status <> 'cancelled'");
    $dup->execute([$ev['year'], $f['first_name'], $f['last_name'], $f['birth_date']]);
    if ($ref = $dup->fetchColumn()) {
        out(['ok' => false, 'error' => "Već si prijavljen za ovo okupljanje (broj prijave: $ref). Za izmjene nam se javi e-mailom."], 409);
    }

    $pdo->beginTransaction();
    $ins = $pdo->prepare('INSERT INTO registrations
        (year, created_at, first_name, last_name, email, phone, birth_date, country, city, address, church, accommodation, tshirt, notes, status, ip_hash)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $ins->execute([
        $ev['year'], now(), $f['first_name'], $f['last_name'], $f['email'], $f['phone'], $f['birth_date'],
        $f['country'], $f['city'], $f['address'], $f['church'] ?: null, $f['accommodation'], $f['tshirt'],
        $f['notes'] ?: null, 'new', client_ip_hash(),
    ]);
    $id = (int)$pdo->lastInsertId();
    $ref = make_ref($id, $f['country'], (int)$ev['year']);
    $pdo->prepare('UPDATE registrations SET ref = ? WHERE id = ?')->execute([$ref, $id]);
    $pdo->commit();
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    error_log('[CBS] register: ' . $e->getMessage());
    out(['ok' => false, 'error' => 'Došlo je do greške na poslužitelju. Pokušaj ponovno ili nam piši na cuvar.brata.svoga@gmail.com.'], 500);
}

/* potvrdni e-mail prijavljenom */
$name = $f['first_name'] . ' ' . $f['last_name'];
$pay = cfg('payments')[$f['country']] ?? '';
$body = "Dragi brate {$f['first_name']},\n\n"
    . "hvala na prijavi za ČBS {$ev['year']}" . (!empty($ev['theme']) ? " – {$ev['theme']}" : '') . " ({$ev['place']}).\n\n"
    . "Tvoj broj prijave: $ref\n\n"
    . "Da bi prijava bila važeća, uplati nepovratnu akontaciju od 20 €.\n"
    . "Svrha uplate: Donacija za ČBS {$ev['year']} – $ref – $name\n\n"
    . "Upute za uplatu (" . CBS_COUNTRIES[$f['country']] . "):\n$pay\n\n"
    . "Ostatak kotizacije i smještaj plaćaju se pri registraciji na okupljanju, isključivo u eurima. Točne iznose potvrdit ćemo uz pozivno pismo.\n\n"
    . "Tvoji podaci:\n"
    . "  Smještaj: " . CBS_ACCOM[$f['accommodation']] . "\n"
    . "  Majica: {$f['tshirt']}\n\n"
    . "Pitanja? Odgovori na ovaj e-mail ili piši na cuvar.brata.svoga@gmail.com.\n\n"
    . "Vidimo se!\nČuvar brata svoga\n" . cfg('site_url') . "\n";
$mailed = send_mail($f['email'], "Prijava za ČBS {$ev['year']} – $ref", $body);

/* obavijest adminu države */
if (!empty(cfg('mail')['notify_admins'])) {
    try {
        // admin države; za "Ostalo" glavni admin
        $s = $f['country'] === 'XX'
            ? db()->prepare("SELECT email FROM admins WHERE (country IS NULL OR country = '') AND email IS NOT NULL AND email <> ''")
            : db()->prepare("SELECT email FROM admins WHERE country = ? AND email IS NOT NULL AND email <> ''");
        $s->execute($f['country'] === 'XX' ? [] : [$f['country']]);
        foreach ($s->fetchAll(PDO::FETCH_COLUMN) as $to) {
            send_mail($to, "Nova prijava: $name ($ref)",
                "Nova prijava za ČBS {$ev['year']}:\n\n$name\n{$f['city']}, " . CBS_COUNTRIES[$f['country']] . "\n{$f['phone']} · {$f['email']}\n\n"
                . "Pregled: " . cfg('site_url') . "/admin/\n");
        }
    } catch (Throwable $e) { error_log('[CBS] notify: ' . $e->getMessage()); }
}

out(['ok' => true, 'ref' => $ref, 'mailed' => $mailed]);
