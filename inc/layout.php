<?php
/* Zajedničko zaglavlje i podnožje svih stranica. */
declare(strict_types=1);
require_once __DIR__ . '/i18n.php';

/* ===== LOGO =====
 * Verzija loga u zaglavlju – zamijeni datoteku kad se odluči finalna verzija za 2027.
 * Sve verzije: assets/brand/ (pregled: assets/brand/index.html)
 */
const CBS_HEADER_LOGO = 'assets/brand/png/cbs-logo-white-256.png';
const CBS_FOOTER_LOGO = 'assets/brand/png/cbs-logo-white-256.png';   // podnožje je crno → bijeli logo

/* Jesu li prijave otvorene (iz api/config.php). Bez config.php → zatvorene. */
function cbs_registration_open(): bool
{
    if (defined('CBS_STATIC')) return false;   // statični pregled nema prijava
    static $open = null;
    if ($open === null) {
        $f = __DIR__ . '/../api/config.php';
        $c = is_file($f) ? (require $f) : [];
        $open = !empty($c['registration_open']);
    }
    return $open;
}

function self_url(string $lang): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $host = preg_replace('/[^a-z0-9.:-]/i', '', (string)($_SERVER['HTTP_HOST'] ?? 'cuvarbratasvoga.com'));
    $path = strtok((string)($_SERVER['REQUEST_URI'] ?? '/'), '?');
    return ($https ? 'https' : 'http') . '://' . $host . $path . ($lang !== CBS_DEFAULT_LANG ? '?lang=' . $lang : '');
}

function page_open(string $page, string $title, string $description, array $opt = []): void
{
    $l = cbs_lang();
    $reg = cbs_registration_open() ? 'open' : 'closed';
    $nav = [
        ['index.php', '', t('nav.home')],
        ['o-nama.php', '', t('nav.about')],
        ['povijest.php', '', t('nav.history')],
        ['mediji.php', '', t('nav.media')],
        ['kontakt.php', '', t('nav.contact')],
    ];
    ?><!doctype html>
<html lang="<?= CBS_LANGS[$l]['html'] ?>" data-reg="<?= $reg ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h_($title) ?></title>
<meta name="description" content="<?= h_($description) ?>">
<meta property="og:title" content="<?= h_($title) ?>">
<meta property="og:description" content="<?= h_($description) ?>">
<meta property="og:image" content="<?= asset('assets/brand/square/cbs-square-dark-1080.jpg') ?>">
<meta property="og:type" content="website">
<meta name="theme-color" content="#1b2a22">
<?php if (!empty($opt['noindex'])): ?><meta name="robots" content="noindex"><?php endif; ?>
<?php if (!defined('CBS_STATIC')): foreach (CBS_LANGS as $code => $info): ?>
<link rel="alternate" hreflang="<?= $info['html'] ?>" href="<?= h_(self_url($code)) ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= h_(self_url(CBS_DEFAULT_LANG)) ?>">
<?php else: ?>
<meta name="robots" content="noindex">
<?php endif; ?>
<link rel="icon" href="<?= asset('assets/brand/favicon/favicon.ico') ?>" sizes="any">
<link rel="icon" type="image/png" href="<?= asset('assets/brand/favicon/favicon-32.png') ?>">
<link rel="apple-touch-icon" href="<?= asset('assets/brand/favicon/apple-touch-icon.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<?php if ($l === 'mk'): /* Barlow nema ćirilicu – za makedonski sličan font s ćirilicom */ ?>
<link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Extra+Condensed:wght@500;600;700;800&family=Fira+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<?php endif; ?>
<link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body>
<a class="skip" href="#main"><?= t('nav.skip') ?></a>

<header class="site-header<?= !empty($opt['solid_header']) ? ' solid' : '' ?>">
  <div class="wrap">
    <a class="brand" href="<?= u('index.php') ?>" aria-label="<?= h_(t('nav.home_aria')) ?>">
      <img src="<?= asset(CBS_HEADER_LOGO) ?>" alt="" width="50" height="45">
      <span><?= t('site.name') ?><small><?= t('site.tagline') ?></small></span>
    </a>
    <details class="lang">
      <summary aria-label="<?= h_(t('nav.lang') . ': ' . CBS_LANGS[$l]['name']) ?>"><img class="flag" src="<?= asset('assets/img/flags/' . CBS_LANGS[$l]['flag'] . '.svg') ?>" width="20" height="15" alt=""><?= CBS_LANGS[$l]['short'] ?></summary>
      <ul>
        <?php foreach (CBS_LANGS as $code => $info): ?>
        <li><a href="<?= h_(page_url($page, $code)) ?>" lang="<?= $info['html'] ?>" hreflang="<?= $info['html'] ?>"<?= $code === $l ? ' aria-current="true"' : '' ?>><img class="flag" src="<?= asset('assets/img/flags/' . $info['flag'] . '.svg') ?>"  width="20" height="15" alt=""><?= h_($info['name']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </details>
    <?php if (empty($opt['no_nav'])): ?>
    <button class="menu-btn" type="button" aria-expanded="false" aria-controls="nav" aria-label="<?= h_(t('nav.menu')) ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
    <nav class="nav" id="nav" aria-label="<?= h_(t('nav.main')) ?>">
      <?php foreach ($nav as $i => [$p, $hash, $label]): ?>
      <a href="<?= u($p, $hash) ?>"<?= $p === $page ? ' aria-current="page"' : '' ?>><?= $label ?></a>
      <?php endforeach; ?>
      <a class="btn btn-gold nav-cta<?= $page === 'prijava.php' ? ' is-current' : '' ?>" href="<?= u('prijava.php') ?>"><?= t('nav.signup_cta') ?></a>
    </nav>
    <?php endif; ?>
  </div>
</header>
<?php
}

function page_close(array $scripts = []): void
{
    ?>
<footer class="site-footer">
  <div class="wrap">
    <div class="cols">
      <div>
        <a class="brand" href="<?= u('index.php') ?>"><img src="<?= asset(CBS_FOOTER_LOGO) ?>" alt="" width="64" height="58"><span><?= t('site.name') ?><small><?= t('site.tagline') ?></small></span></a>
        <p style="margin-top:16px;max-width:42ch"><?= t('footer.verse') ?></p>
      </div>
      <div>
        <h4><?= t('footer.pages') ?></h4>
        <ul>
          <li><a href="<?= u('o-nama.php') ?>"><?= t('nav.about') ?></a></li>
          <li><a href="<?= u('povijest.php') ?>"><?= t('nav.history') ?></a></li>
          <li><a href="<?= u('mediji.php') ?>"><?= t('nav.media') ?></a></li>
          <li><a href="<?= u('prijava.php') ?>"><?= t('prij.h1') ?></a></li>
          <li><a href="<?= u('kontakt.php') ?>"><?= t('nav.contact') ?></a></li>
          <li><a href="<?= u('privatnost.php') ?>"><?= t('footer.privacy') ?></a></li>
        </ul>
      </div>
      <div>
        <h4><?= t('footer.follow') ?></h4>
        <ul><li><a href="https://www.youtube.com/@CuvarBrataSvoga" target="_blank" rel="noopener">YouTube ↗</a></li><li><a href="https://www.facebook.com/CuvarBrataSvoga/" target="_blank" rel="noopener">Facebook ↗</a></li><li><a href="mailto:cuvar.brata.svoga@gmail.com">cuvar.brata.svoga@gmail.com</a></li></ul>
      </div>
    </div>
    <div class="legal"><span>© <span id="year-now"><?= date('Y') ?></span> <?= t('site.name') ?></span><span><?= t('footer.motto') ?></span></div>
  </div>
</footer>
<script>window.CBS_LANG = <?= json_encode(cbs_lang()) ?>; window.CBS_T = <?= json_encode(cbs_js_dict(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;</script>
<?php foreach (array_merge(['assets/js/config.js', 'assets/js/talks.js', 'assets/js/main.js'], $scripts) as $s): ?>
<script src="<?= asset($s) ?>"></script>
<?php endforeach; ?>
</body>
</html>
<?php
}

function h_(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

/* ===== FOTOGRAFIJE =====
 * Datoteke su u assets/img/photos/<ime>-800.jpg i <ime>-1600.jpg (1600 nije uvijek tu).
 * photo('logorska-vatra') ispisuje <img> s obje veličine; preglednik bira odgovarajuću. */
function photo_src(string $name, int $w = 1600): string
{
    $big = __DIR__ . "/../assets/img/photos/$name-1600.jpg";
    if ($w >= 1600 && is_file($big)) return asset("assets/img/photos/$name-1600.jpg");
    return asset("assets/img/photos/$name-800.jpg");
}
function photo(string $name, string $alt = '', string $sizes = '(max-width: 900px) 100vw, 50vw', bool $lazy = true): string
{
    $srcset = photo_src($name, 800) . ' 800w';
    if (is_file(__DIR__ . "/../assets/img/photos/$name-1600.jpg")) $srcset .= ', ' . photo_src($name, 1600) . ' 1600w';
    return '<img src="' . photo_src($name, 800) . '" srcset="' . $srcset . '" sizes="' . h_($sizes) . '" alt="' . h_($alt) . '"'
        . ($lazy ? ' loading="lazy" decoding="async"' : '') . '>';
}
/* Pozadina vrha stranice (page-hero) */
function hero_bg(string $name): string
{
    /* url() mora biti u inline stilu: u CSS varijabli bi se putanja čitala relativno prema style.css */
    return ' has-photo" style="background-image:var(--hero-shade),url(\'' . photo_src($name, 1600) . '\')';
}
