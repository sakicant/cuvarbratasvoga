<?php
/* Zajedničke funkcije: baza, sesija, sigurnost, e-mail. Zahtijeva PHP 8.0+ (pdo_sqlite ili pdo_mysql, mbstring). */
declare(strict_types=1);

const CBS_COUNTRIES = [
    'HR' => 'Hrvatska',
    'BA' => 'Bosna i Hercegovina',
    'RS' => 'Srbija',
    'ME' => 'Crna Gora',
    'MK' => 'Sjeverna Makedonija',
    'SI' => 'Slovenija',
    'XX' => 'Ostalo',
];
const CBS_ACCOM = [
    'own_tent'        => 'Imam vlastiti šator',
    'need_tent'       => 'Trebam šator',
    'own_arrangement' => 'Sam sam se pobrinuo za smještaj',
];
const CBS_SHIRTS = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
const CBS_STATUS = [
    'new'       => 'Čeka akontaciju',
    'paid'      => 'Akontacija uplaćena',
    'cancelled' => 'Otkazano',
];

function cfg(?string $key = null): mixed
{
    static $c = null;
    if ($c === null) {
        $f = __DIR__ . '/config.php';
        if (!is_file($f)) {
            http_response_code(500);
            exit('Nedostaje api/config.php – kopiraj api/config.sample.php u api/config.php i upiši postavke.');
        }
        $c = require $f;
        date_default_timezone_set($c['timezone'] ?? 'Europe/Zagreb');
    }
    return $key === null ? $c : ($c[$key] ?? null);
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo) return $pdo;
    $c = cfg('db');
    if (str_starts_with($c['dsn'], 'sqlite:')) {
        $dir = dirname(substr($c['dsn'], 7));
        if (!is_dir($dir)) mkdir($dir, 0770, true);
    }
    $pdo = new PDO($c['dsn'], $c['user'] ?? null, $c['pass'] ?? null, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    if (is_sqlite()) {
        $pdo->exec('PRAGMA journal_mode=WAL');
        $pdo->exec('PRAGMA busy_timeout=5000');
    }
    migrate($pdo);
    return $pdo;
}

function is_sqlite(): bool
{
    return str_starts_with(cfg('db')['dsn'], 'sqlite:');
}

function migrate(PDO $pdo): void
{
    $sqlite = is_sqlite();
    $pk  = $sqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
    $s   = $sqlite ? 'TEXT' : 'VARCHAR(190)';
    $t   = 'TEXT';
    $end = $sqlite ? '' : ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    $pdo->exec("CREATE TABLE IF NOT EXISTS registrations (
        id $pk,
        year INT NOT NULL,
        ref $s,
        created_at $s NOT NULL,
        first_name $s NOT NULL,
        last_name $s NOT NULL,
        email $s NOT NULL,
        phone $s NOT NULL,
        birth_date $s NOT NULL,
        country $s NOT NULL,
        city $s NOT NULL,
        address $s NOT NULL,
        church $s,
        accommodation $s NOT NULL,
        tshirt $s NOT NULL,
        notes $t,
        status $s NOT NULL DEFAULT 'new',
        paid_at $s,
        admin_note $t,
        updated_at $s,
        updated_by $s,
        ip_hash $s
    )$end");
    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id $pk,
        username $s NOT NULL UNIQUE,
        name $s NOT NULL,
        email $s,
        country $s,
        password_hash $s NOT NULL,
        created_at $s NOT NULL,
        last_login $s
    )$end");
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id $pk,
        created_at $s NOT NULL,
        name $s NOT NULL,
        email $s NOT NULL,
        country $s NOT NULL,
        message $t NOT NULL,
        lang $s,
        status $s NOT NULL DEFAULT 'new',
        handled_by $s,
        handled_at $s,
        sent_to $t,
        ip_hash $s
    )$end");
    $pdo->exec("CREATE TABLE IF NOT EXISTS attempts (
        id $pk,
        kind $s NOT NULL,
        ip_hash $s NOT NULL,
        at INT NOT NULL
    )$end");
    // dodani stupci (za baze kreirane prije)
    $cols = $sqlite
        ? array_column($pdo->query('PRAGMA table_info(registrations)')->fetchAll(), 'name')
        : $pdo->query('SHOW COLUMNS FROM registrations')->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('lang', $cols, true)) $pdo->exec("ALTER TABLE registrations ADD COLUMN lang $s");

    foreach ([
        'CREATE INDEX idx_reg_year_country ON registrations (year, country)',
        'CREATE INDEX idx_att ON attempts (kind, ip_hash, at)',
    ] as $sql) {
        try { $pdo->exec($sqlite ? str_replace('CREATE INDEX', 'CREATE INDEX IF NOT EXISTS', $sql) : $sql); } catch (PDOException $e) { /* već postoji */ }
    }
}

function now(): string { return date('Y-m-d H:i:s'); }

function h(mixed $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function client_ip_hash(): string
{
    return substr(hash_hmac('sha256', $_SERVER['REMOTE_ADDR'] ?? '0', (string)cfg('secret')), 0, 32);
}

/* Ograničenje broja pokušaja (prijava / login) po IP adresi. */
function too_many(string $kind, int $max, int $window): bool
{
    $s = db()->prepare('SELECT COUNT(*) FROM attempts WHERE kind = ? AND ip_hash = ? AND at > ?');
    $s->execute([$kind, client_ip_hash(), time() - $window]);
    return (int)$s->fetchColumn() >= $max;
}
function record_attempt(string $kind): void
{
    db()->prepare('INSERT INTO attempts (kind, ip_hash, at) VALUES (?, ?, ?)')->execute([$kind, client_ip_hash(), time()]);
    if (random_int(1, 50) === 1) db()->prepare('DELETE FROM attempts WHERE at < ?')->execute([time() - 86400]);
}
function clear_attempts(string $kind): void
{
    db()->prepare('DELETE FROM attempts WHERE kind = ? AND ip_hash = ?')->execute([$kind, client_ip_hash()]);
}

function clean(mixed $v, int $max = 190): string
{
    $v = is_scalar($v) ? (string)$v : '';
    $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $v) ?? '';
    $v = trim(preg_replace('/[ \t]+/u', ' ', $v) ?? '');
    return mb_substr($v, 0, $max);
}

function age_at(string $birth, string $on): int
{
    return (new DateTime($birth))->diff(new DateTime($on))->y;
}

function make_ref(int $id, string $country, int $year): string
{
    return sprintf('CBS%02d-%s-%04d', $year % 100, $country, $id);
}

/* ---------- e-mail ---------- */
function send_mail(string $to, string $subject, string $body, ?string $replyTo = null): bool
{
    $m = cfg('mail');
    if (empty($m['enabled']) || !filter_var($to, FILTER_VALIDATE_EMAIL)) return false;
    $from = $m['from'];
    if ($replyTo !== null && !filter_var($replyTo, FILTER_VALIDATE_EMAIL)) $replyTo = null;
    $headers = [
        'From: ' . mb_encode_mimeheader($m['from_name'] ?? 'ČBS', 'UTF-8') . " <$from>",
        'Reply-To: ' . ($replyTo ?? $m['reply_to'] ?? $from),
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
    ];
    $ok = @mail($to, mb_encode_mimeheader($subject, 'UTF-8'), $body, implode("\r\n", $headers), '-f' . $from);
    if (!$ok) error_log("[CBS] slanje e-maila nije uspjelo: $to / $subject");
    return $ok;
}

/* ---------- sesija i admini ---------- */
function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name('cbs_admin');
    session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'httponly' => true, 'samesite' => 'Strict', 'secure' => $https]);
    session_start();
    // automatska odjava nakon 2 sata neaktivnosti
    if (!empty($_SESSION['aid']) && (time() - ($_SESSION['seen'] ?? 0)) > 7200) {
        $_SESSION = [];
        session_regenerate_id(true);
    }
    $_SESSION['seen'] = time();
}

function csrf_token(): string
{
    start_session();
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">';
}
function csrf_check(): void
{
    start_session();
    if (!is_string($_POST['csrf'] ?? null) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        http_response_code(400);
        exit('Sesija je istekla. Vrati se i osvježi stranicu.');
    }
}

function current_admin(): ?array
{
    start_session();
    if (empty($_SESSION['aid'])) return null;
    $s = db()->prepare('SELECT * FROM admins WHERE id = ?');
    $s->execute([$_SESSION['aid']]);
    return $s->fetch() ?: null;
}

function is_super(array $admin): bool { return $admin['country'] === null || $admin['country'] === ''; }

/* Vrati WHERE dio koji adminu ograničava pristup na njegovu državu. */
function scope(array $admin, string $alias = ''): array
{
    if (is_super($admin)) return ['1=1', []];
    return [($alias ? "$alias." : '') . 'country = ?', [$admin['country']]];
}
