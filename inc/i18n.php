<?php
/*
 * Jezici stranice. Tekstovi su u mapi /lang (hr.php je izvor; ostali jezici nadopunjuju/prevode).
 * Ako u nekom jeziku nedostaje ključ, prikazuje se hrvatski tekst.
 */
declare(strict_types=1);

const CBS_LANGS = [
    'hr'  => ['name' => 'Hrvatski',    'short' => 'HR', 'html' => 'hr'],
    'bs'  => ['name' => 'Bosanski',    'short' => 'BS', 'html' => 'bs'],
    'sr'  => ['name' => 'Srpski',      'short' => 'SR', 'html' => 'sr-Latn'],
    'cnr' => ['name' => 'Crnogorski',  'short' => 'CG', 'html' => 'cnr'],
    'mk'  => ['name' => 'Македонски',  'short' => 'MK', 'html' => 'mk'],
    'sl'  => ['name' => 'Slovenščina', 'short' => 'SL', 'html' => 'sl'],
    'en'  => ['name' => 'English',     'short' => 'EN', 'html' => 'en'],
];
const CBS_DEFAULT_LANG = 'hr';

/* Trenutni jezik: ?lang= → kolačić → jezik preglednika → hrvatski. */
function cbs_lang(?string $force = null): string
{
    static $lang = null;
    if ($force !== null && isset(CBS_LANGS[$force])) return $lang = $force;
    if ($lang !== null) return $lang;

    $q = $_GET['lang'] ?? null;
    if (is_string($q) && isset(CBS_LANGS[$q])) {
        if (!headers_sent()) setcookie('cbs_lang', $q, ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax']);
        return $lang = $q;
    }
    $c = $_COOKIE['cbs_lang'] ?? null;
    if (is_string($c) && isset(CBS_LANGS[$c])) return $lang = $c;

    // Jezik preglednika: domaći jezici imaju prednost (mnogi u regiji imaju preglednik na engleskom,
    // ali uz njega navedu i svoj jezik). Engleski samo ako nema nijednog domaćeg.
    $al = strtolower((string)($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''));
    $hasEn = false;
    foreach (explode(',', $al) as $part) {
        $tag = trim(explode(';', $part)[0]);
        if ($tag === '') continue;
        if ($tag === 'cnr' || str_starts_with($tag, 'sr-me') || str_starts_with($tag, 'sh-me')) return $lang = 'cnr';
        $p = explode('-', $tag)[0];
        if ($p === 'sh') return $lang = 'sr';
        if ($p === 'hr' || $p === 'bs' || $p === 'sr' || $p === 'mk' || $p === 'sl') return $lang = $p;
        if ($p === 'en') $hasEn = true;
    }
    return $lang = ($hasEn && $al !== '' ? 'en' : CBS_DEFAULT_LANG);
}

function cbs_dict(): array
{
    static $cache = [];
    $l = cbs_lang();
    if (!isset($cache[$l])) {
        $base = require __DIR__ . '/../lang/' . CBS_DEFAULT_LANG . '.php';
        $over = ($l !== CBS_DEFAULT_LANG && is_file(__DIR__ . "/../lang/$l.php")) ? require __DIR__ . "/../lang/$l.php" : [];
        $cache[$l] = array_replace($base, $over);
    }
    return $cache[$l];
}

/* Tekst za ključ. Tekstovi smiju sadržavati HTML (dolaze iz naših datoteka). {ime} se zamjenjuje vrijednošću iz $vars. */
function t(string $key, array $vars = []): string
{
    $s = cbs_dict()[$key] ?? $key;
    if (is_array($s)) $s = (string)reset($s);
    foreach ($vars as $k => $v) $s = str_replace('{' . $k . '}', (string)$v, $s);
    return $s;
}

/* Poveznica na stranicu u trenutnom jeziku. */
function u(string $page = 'index.php', string $hash = ''): string
{
    $l = cbs_lang();
    return $page . ($l !== CBS_DEFAULT_LANG ? '?lang=' . $l : '') . $hash;
}

/* Tekstovi potrebni JavaScriptu (ključevi js.* i country.*). */
function cbs_js_dict(): array
{
    $out = [];
    foreach (cbs_dict() as $k => $v) {
        if (str_starts_with($k, 'js.') || str_starts_with($k, 'country.') || preg_match('/^hist\.y\d{4}_t$/', $k)) $out[$k] = $v;
    }
    return $out;
}
