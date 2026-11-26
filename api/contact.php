<?php
/* POST /api/contact.php — poruka s kontakt obrasca. Sprema se i šalje koordinatoru (adminu) države pošiljatelja. */
declare(strict_types=1);
require __DIR__ . '/lib.php';
require __DIR__ . '/../inc/i18n.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

function out(array $data, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(['ok' => false, 'error' => 'Method not allowed'], 405);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && parse_url($origin, PHP_URL_HOST) !== parse_url('http://' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST)) {
    out(['ok' => false, 'error' => t('api.forbidden')], 403);
}

$in = json_decode((string)file_get_contents('php://input', false, null, 0, 20000), true);
if (!is_array($in)) $in = $_POST;
$lang = is_string($in['lang'] ?? null) && isset(CBS_LANGS[$in['lang']]) ? $in['lang'] : cbs_lang();
cbs_lang($lang);

if (!empty($in['website'])) out(['ok' => true]);   // zamka za botove

try {
    if (too_many('contact', 8, 3600)) out(['ok' => false, 'error' => t('api.too_many')], 429);

    $f = [
        'name'    => clean($in['name'] ?? '', 100),
        'email'   => mb_strtolower(clean($in['email'] ?? '', 160)),
        'country' => clean($in['country'] ?? '', 2),
        'message' => mb_substr(trim((string)($in['message'] ?? '')), 0, 4000),
    ];
    $err = [];
    if (mb_strlen($f['name']) < 2) $err['name'] = t('js.err.name');
    if (!filter_var($f['email'], FILTER_VALIDATE_EMAIL)) $err['email'] = t('js.err.email');
    if (!isset(CBS_COUNTRIES[$f['country']])) $err['country'] = t('js.err.country');
    if (mb_strlen($f['message']) < 10) $err['message'] = t('js.err.message');
    if (empty($in['consent'])) $err['consent'] = t('js.err.consent');
    if ($err) out(['ok' => false, 'error' => t('js.err.check'), 'fields' => $err], 422);

    record_attempt('contact');
    $pdo = db();

    /* primatelji: admini te države s e-mailom → adresa iz config.php za tu državu → opća adresa */
    if ($f['country'] === 'XX') {
        $s = $pdo->query("SELECT email FROM admins WHERE (country IS NULL OR country = '') AND email IS NOT NULL AND email <> ''");
    } else {
        $s = $pdo->prepare("SELECT email FROM admins WHERE country = ? AND email IS NOT NULL AND email <> ''");
        $s->execute([$f['country']]);
    }
    $to = array_values(array_unique(array_filter($s->fetchAll(PDO::FETCH_COLUMN), fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))));
    if (!$to && !empty(cfg('contact_emails')[$f['country']])) $to = [cfg('contact_emails')[$f['country']]];
    if (!$to) $to = [cfg('contact_fallback') ?: (cfg('mail')['reply_to'] ?? '')];

    $pdo->prepare('INSERT INTO messages (created_at, name, email, country, message, lang, status, sent_to, ip_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)')
        ->execute([now(), $f['name'], $f['email'], $f['country'], $f['message'], $lang, 'new', implode(', ', $to), client_ip_hash()]);
} catch (Throwable $e) {
    error_log('[CBS] contact: ' . $e->getMessage());
    out(['ok' => false, 'error' => t('api.server')], 500);
}

/* e-mail koordinatoru (na hrvatskom – admin strana); „Odgovori“ ide izravno pošiljatelju */
$country = CBS_COUNTRIES[$f['country']];
$body = "Nova poruka s web stranice Čuvar brata svoga\n\n"
    . "Od: {$f['name']} <{$f['email']}>\n"
    . "Država: $country\n"
    . "Jezik stranice: " . strtoupper($lang) . "\n\n"
    . "----------------------------------------\n"
    . $f['message'] . "\n"
    . "----------------------------------------\n\n"
    . "Odgovori izravno na ovaj e-mail – odgovor ide pošiljatelju.\n"
    . "Sve poruke: " . cfg('site_url') . "/admin/?a=messages\n";
$mailed = false;
foreach ($to as $addr) {
    $mailed = send_mail($addr, "Poruka s web stranice ČBS – {$f['name']} ($country)", $body, $f['email']) || $mailed;
}

out(['ok' => true, 'mailed' => $mailed]);
