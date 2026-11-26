<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/layout.php';
page_open('o-nama.php', t('about.title'), t('about.description'));

/* Koordinatori po državama (imena se ne prevode). */
$team = [
    'HR' => ['Oliver Buljat',         'EPC Milost i Istina, Karlovac'],
    'BA' => ['Slavko Hadžić',         'Udruženje „Dobra Vijest“'],
    'RS' => ['Miroslav Radovanović',  ''],
    'ME' => ['Dragan Novaković',      'Hrišćanski centar „Svetionik“'],
    'MK' => ['Zoran Kolev',           'Evangelska crkva, Gevgelija'],
    'SI' => ['Srečko Kranjc',         'BC Emanuel'],
];
$flags = ['HR' => 'hr', 'BA' => 'ba', 'RS' => 'rs', 'ME' => 'me', 'MK' => 'mk', 'SI' => 'si'];
?>
<main id="main">

<section class="page-hero<?= hero_bg('grupa-zajedno') ?>">
  <div class="wrap">
    <span class="eyebrow"><?= t('about.eyebrow') ?></span>
    <h1><?= t('about.h1') ?></h1>
    <p class="lead" style="max-width:68ch"><?= t('about.lead') ?></p>
  </div>
</section>

<section class="section">
  <div class="wrap split">
    <div class="prose reveal">
      <span class="eyebrow"><?= t('about.who_eyebrow') ?></span>
      <h2><?= t('about.who_title') ?></h2>
      <figure class="figure"><?= photo('braca-generacije') ?></figure>
      <p><?= t('about.who_p1') ?></p>
      <p><?= t('about.who_p2') ?></p>
      <p><?= t('about.who_p3') ?></p>
    </div>
    <div class="reveal">
      <span class="eyebrow"><?= t('about.mission_eyebrow') ?></span>
      <h2><?= t('about.mission_title') ?></h2>
      <p class="mission"><?= t('about.mission_p') ?></p>
      <p class="verse-inline"><?= t('hist.verse') ?><cite><?= t('hist.verse_cite') ?></cite></p>
    </div>
  </div>
</section>

<section class="section dark">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow"><?= t('about.values_eyebrow') ?></span>
      <h2><?= t('about.values_title') ?></h2>
    </div>
    <ol class="values reveal">
      <?php for ($i = 1; $i <= 5; $i++): ?>
      <li><span class="n"><?= $i ?></span><h3><?= t("about.v{$i}_t") ?></h3><p><?= t("about.v{$i}_d") ?></p></li>
      <?php endfor; ?>
    </ol>
    <div class="photo-row reveal">
      <figure class="figure figure-cap"><?= photo('slavljenje-ruke') ?><figcaption><?= t('about.v3_t') ?></figcaption></figure>
      <figure class="figure figure-cap"><?= photo('potezanje-uzeta') ?><figcaption><?= t('about.v4_t') ?></figcaption></figure>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap split">
    <div class="prose reveal">
      <span class="eyebrow"><?= t('about.year_eyebrow') ?></span>
      <h2><?= t('about.year_title') ?></h2>
      <p><?= t('about.year_p') ?></p>
      <figure class="figure"><?= photo('logorska-vatra') ?></figure>
      <p><a class="btn btn-dark" href="<?= u('povijest.php') ?>"><?= t('about.cta_film') ?> →</a></p>
    </div>
    <div class="reveal">
      <span class="eyebrow"><?= t('about.team_eyebrow') ?></span>
      <h2><?= t('about.team_title') ?></h2>
      <p class="muted"><?= t('about.team_lead') ?></p>
      <ul class="team">
        <?php foreach ($team as $c => [$name, $org]): ?>
        <li>
          <img class="flag" src="<?= asset('assets/img/flags/' . $flags[$c] . '.svg') ?>" width="28" height="21" alt="">
          <div><span class="team-country"><?= t('country.' . $c) ?></span><b><?= h_($name) ?></b><?php if ($org): ?><small><?= h_($org) ?></small><?php endif; ?></div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<section class="section bone">
  <div class="wrap">
    <div class="signup reveal">
      <div>
        <h2><?= t('about.cta_title') ?></h2>
        <p class="muted"><?= t('about.cta_text') ?></p>
      </div>
      <div class="actions">
        <a class="btn btn-gold" href="<?= u('kontakt.php') ?>"><?= t('contact.write_btn') ?> →</a>
        <a class="btn btn-ghost" href="<?= u('povijest.php') ?>"><?= t('about.cta_film') ?></a>
      </div>
    </div>
  </div>
</section>

</main>
<?php page_close();
