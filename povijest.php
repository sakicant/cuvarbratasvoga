<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/layout.php';
page_open('povijest.php', t('hist.title'), t('hist.description'));

/* godina => [poveznica na propovijedi?, istaknuto?] */
$years = [
    2027 => ['link' => 'signup', 'big' => true],
    2026 => ['link' => 'talks'],
    2024 => ['link' => 'talks'],
    2023 => ['link' => 'talks'],
    2022 => ['link' => 'talks'],
    2020 => ['link' => 'recordings'],
    2019 => ['link' => 'talks'],
    2017 => ['link' => 'talks'],
    2016 => ['link' => 'talks'],
    2015 => ['link' => null],
    2014 => ['link' => 'talks'],
    2013 => ['link' => 'talks'],
    2012 => ['link' => 'talks'],
    2010 => ['link' => 'talks', 'big' => true],
];
?>
<main id="main">

<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow"><?= t('hist.eyebrow') ?></span>
    <h1><?= t('hist.h1') ?></h1>
    <p class="lead"><?= t('hist.lead') ?></p>
    <div class="lite" data-id="jAlQv6GD9oI" data-title="<?= h_(t('hist.h1')) ?>" style="margin-top:40px">
      <button type="button" aria-label="<?= h_(t('hist.film_aria')) ?>">
        <img src="https://i.ytimg.com/vi/jAlQv6GD9oI/maxresdefault.jpg" alt="" loading="eager">
        <span class="play-big"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg></span>
        <span class="lite-cap"><?= t('hist.film_cap') ?></span>
      </button>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap split">
    <div class="prose reveal">
      <span class="eyebrow"><?= t('hist.what_eyebrow') ?></span>
      <h2><?= t('hist.what_title') ?></h2>
      <p><?= t('hist.what_p1') ?></p>
      <p><?= t('hist.what_p2') ?></p>
      <p><?= t('hist.what_p3') ?></p>
    </div>
    <div class="reveal">
      <span class="eyebrow"><?= t('hist.pillars') ?></span>
      <div class="pillars">
        <div class="pillar"><span class="n">1</span><h3><?= t('hist.p1_t') ?></h3><p><?= t('hist.p1_d') ?></p></div>
        <div class="pillar"><span class="n">2</span><h3><?= t('hist.p2_t') ?></h3><p><?= t('hist.p2_d') ?></p></div>
      </div>
      <p style="margin-top:22px"><button class="btn btn-dark" type="button" data-video="z5zPth-s2jk" data-title="Što je ČBS" data-sub="Oliver Buljat · ČBS 2023"><?= t('hist.watch') ?></button></p>
    </div>
  </div>
</section>

<section class="section bone">
  <div class="wrap">
    <p class="verse-big reveal"><?= t('hist.verse') ?><cite><?= t('hist.verse_cite') ?></cite></p>
  </div>
</section>

<section class="section dark">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow"><?= t('hist.years_eyebrow') ?></span>
      <h2><?= t('hist.years_title') ?></h2>
      <p class="lead"><?= t('hist.years_lead') ?></p>
    </div>
    <div class="group-strip reveal" aria-hidden="true">
      <figure class="figure"><?= photo('grupa-1', '', '(max-width: 700px) 50vw, 25vw') ?></figure>
      <figure class="figure"><?= photo('grupa-2', '', '(max-width: 700px) 50vw, 25vw') ?></figure>
      <figure class="figure"><?= photo('grupa-3', '', '(max-width: 700px) 50vw, 25vw') ?></figure>
      <figure class="figure"><?= photo('grupa-4', '', '(max-width: 700px) 50vw, 25vw') ?></figure>
      <figure class="figure"><?= photo('grupa-5', '', '(max-width: 700px) 50vw, 25vw') ?></figure>
      <figure class="figure"><?= photo('grupa-6', '', '(max-width: 700px) 50vw, 25vw') ?></figure>
      <figure class="figure"><?= photo('grupa-7', '', '(max-width: 700px) 50vw, 25vw') ?></figure>
      <figure class="figure"><?= photo('grupa-8', '', '(max-width: 700px) 50vw, 25vw') ?></figure>
    </div>
    <ol class="timeline reveal">
      <?php foreach ($years as $y => $o):
          $desc = t("hist.y{$y}_d");
          $link = '';
          if ($o['link'] === 'talks') $link = '<a href="' . u('mediji.php', "#$y") . '">' . t('hist.talks_link') . '</a>';
          if ($o['link'] === 'recordings') $link = '<a href="' . u('mediji.php', "#$y") . '">' . t('hist.recordings_link') . '</a>';
          if ($o['link'] === 'signup') $link = '<a href="' . u('prijava.php') . '">' . t('nav.signup') . '</a>';
          $line = implode(' · ', array_filter([$desc, $link], fn($v) => $v !== ''));
      ?>
      <li<?= !empty($o['big']) ? ' class="big"' : '' ?>><div class="y"><?= $y ?></div><div><h3><?= t("hist.y{$y}_t") ?></h3><?php if ($line !== ''): ?><p><?= $line ?></p><?php endif; ?><?php if ($y === 2019): ?><figure class="tl-photo"><?= photo('evangelizacija-ulica', '', '420px') ?></figure><?php endif; ?></div></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow"><?= t('hist.more_eyebrow') ?></span>
      <h2><?= t('hist.more_title') ?></h2>
    </div>
    <div class="video-grid reveal" data-ids="z5zPth-s2jk,0Cz-Jn_0evk,39Qd53JDycg,CQL0wFO066k,GKGpF8m1-9c,b4IkiBaUX6A"></div>
  </div>
</section>

</main>
<?php page_close();
