<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/layout.php';
page_open('mediji.php', t('media.title'), t('media.description'));
?>
<main id="main">

<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow"><?= t('media.eyebrow') ?></span>
    <h1><?= t('media.h1') ?></h1>
    <p class="lead"><?= t('media.lead') ?></p>
    <p style="margin:26px 0 0"><a class="btn btn-ghost" href="https://www.youtube.com/@CuvarBrataSvoga" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2 31 31 0 0 0 .5 12a31 31 0 0 0 .5 4.8 3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.8 31 31 0 0 0-.5-4.8zM9.8 15.1V8.9L15.2 12z"/></svg>
      <?= t('media.yt') ?></a></p>
  </div>
</section>

<div class="filters">
  <div class="wrap">
    <div class="year-chips" id="year-chips" role="group" aria-label="<?= h_(t('media.years_aria')) ?>"></div>
    <label class="search">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
      <input id="q" type="search" placeholder="<?= h_(t('media.search_ph')) ?>" aria-label="<?= h_(t('media.search_aria')) ?>">
    </label>
    <span class="muted" id="result-count" aria-live="polite"></span>
  </div>
</div>

<section class="wrap" style="padding-bottom:96px">
  <div id="media"></div>
</section>

</main>
<?php page_close();
