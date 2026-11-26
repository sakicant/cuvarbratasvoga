<?php
/*
 * Statični pregled stranice za Vercel (Vercel ne pokreće PHP).
 * Pokreni nakon svake izmjene teksta ili izgleda:   php tools/build-preview.php
 * Rezultat: mapa /preview s HTML stranicama za svih 7 jezika (index.html, index.mk.html, povijest.en.html …).
 * Prijava i admin rade samo na pravom PHP poslužitelju – u pregledu su skriveni.
 */
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }

define('CBS_STATIC', true);
define('CBS_ASSET_PREFIX', '../');
$root = dirname(__DIR__);
$out = $root . '/preview';
if (!is_dir($out)) mkdir($out, 0775, true);

// stare datoteke pregleda
foreach (glob($out . '/*.html') as $old) unlink($old);

require_once $root . '/inc/i18n.php';

$pages = ['index', 'o-nama', 'povijest', 'mediji', 'prijava', 'kontakt', 'privatnost'];
$count = 0;
foreach (array_keys(CBS_LANGS) as $lang) {
    cbs_lang($lang);
    foreach ($pages as $p) {
        $render = static function (string $file): string {
            ob_start();
            include $file;
            return (string)ob_get_clean();
        };
        $html = $render("$root/$p.php");
        $name = $p . ($lang !== CBS_DEFAULT_LANG ? ".$lang" : '') . '.html';
        file_put_contents("$out/$name", $html);
        $count++;
    }
}
echo "preview/: $count stranica (" . count(CBS_LANGS) . " jezika × " . count($pages) . " stranice)\n";
