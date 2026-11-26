<?php
/*
 * Provjera prijevoda:  php lang/check.php
 * Uspoređuje sve jezike s hr.php: nedostaju li tekstovi, ima li viška, jesu li {zamjene} i HTML oznake iste.
 */
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }

$hr = require __DIR__ . '/hr.php';
$tags = function (string $s): array {
    preg_match_all('/\{[a-z_]+\}|<\/?[a-z]+/i', $s, $m);
    $t = $m[0]; sort($t); return $t;
};
$problems = 0;
foreach (glob(__DIR__ . '/*.php') as $file) {
    $code = basename($file, '.php');
    if ($code === 'hr' || $code === 'check') continue;
    $d = require $file;
    $out = [];
    foreach (array_diff(array_keys($hr), array_keys($d)) as $k) $out[] = "  nedostaje: $k";
    foreach (array_diff(array_keys($d), array_keys($hr)) as $k) $out[] = "  višak:     $k";
    foreach ($hr as $k => $v) {
        if (!isset($d[$k]) || is_array($v)) continue;
        if (!is_string($d[$k])) { $out[] = "  nije tekst: $k"; continue; }
        if ($tags($v) !== $tags($d[$k])) $out[] = "  zamjene/HTML se razlikuju: $k";
        if ($v !== '' && trim($d[$k]) === '') $out[] = "  prazno: $k";
    }
    echo strtoupper($code) . ': ' . (count($d)) . ' tekstova' . ($out ? '' : ' – OK') . "\n";
    if ($out) { echo implode("\n", $out) . "\n"; $problems += count($out); }
}
exit($problems ? 1 : 0);
