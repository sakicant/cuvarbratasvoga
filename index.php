<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/layout.php';
page_open('index.php', t('home.title'), t('home.description'));
$fb = 'https://www.facebook.com/CuvarBrataSvoga/';
?>
<main id="main">

<!-- HERO -->
<section class="hero">
  <div class="wrap">
    <div>
      <div class="notice only-past">
        <div><strong><?= t('home.past_title') ?></strong><br><?= t('home.past_text', ['media' => u('mediji.php')]) ?></div>
      </div>
      <h1><?= t('site.name') ?> <em><?= t('event.place') ?></em></h1>
      <p class="hero-verse"><?= t('home.verse') ?><cite><?= t('home.verse_ref') ?></cite></p>
      <dl class="hero-facts">
        <div><dt><?= t('home.when') ?></dt><dd data-ev="dateLabel">24. – 27. 6. 2027.</dd></div>
        <div><dt><?= t('home.where') ?></dt><dd><?= t('event.place') ?></dd></div>
        <div><dt><?= t('home.town') ?></dt><dd><?= t('event.town') ?></dd></div>
      </dl>
      <div class="countdown only-live" aria-label="<?= h_(t('home.countdown')) ?>"></div>
      <div class="hero-actions">
        <a class="btn btn-gold only-live" href="<?= u('prijava.php') ?>"><?= t('home.btn_signup') ?></a>
        <a class="btn btn-gold only-past" href="<?= u('mediji.php') ?>"><?= t('home.btn_talks') ?></a>
        <a class="btn btn-ghost only-live" href="<?= u('mediji.php') ?>"><?= t('home.btn_prev') ?></a>
      </div>
    </div>
    <div class="hero-ticket" aria-hidden="true">
      <img src="<?= asset('assets/brand/png/cbs-logo-white-512.png') ?>" alt="" width="150" height="136">
      <div class="t-no">ČBS <span data-ev="year">2027</span></div>
      <div class="t-date"><span data-ev="dateLabel">24. – 27. 6. 2027.</span></div>
      <div class="t-place"><?= t('event.place') ?><small><?= t('event.town') ?></small></div>
      <div class="t-theme"><?= t('home.theme') ?> <?= t('event.theme') ?></div>
    </div>
  </div>
</section>

<!-- STATISTIKA -->
<section class="stats" aria-label="<?= h_(t('stats.aria')) ?>">
  <div class="wrap">
    <div class="stat"><b>17</b><span><?= t('stats.years') ?></span></div>
    <div class="stat"><b>6</b><span><?= t('stats.countries') ?></span></div>
    <div class="stat"><b>55+</b><span><?= t('stats.talks') ?></span></div>
    <div class="stat"><b>1</b><span><?= t('stats.god') ?></span></div>
  </div>
</section>

<!-- OKUPLJANJE -->
<section class="section" id="okupljanje">
  <div class="wrap split">
    <div class="prose reveal">
      <span class="eyebrow"><?= t('invite.eyebrow') ?></span>
      <h2><?= t('invite.title') ?></h2>
      <?php /* POZIVNO PISMO 2027: dok nije objavljeno, prikazuje se „učitavanje“.
               Kad bude gotovo: obriši div.ph-letter i vrati odlomke iz lang datoteka (invite.grace, invite.p1…p3, invite.quote). */ ?>
      <div class="ph-letter" aria-hidden="true">
        <div class="ph-lines is-short"><i></i><i></i></div>
        <div class="ph-lines"><i></i><i></i><i></i><i></i><i></i></div>
        <div class="ph-lines"><i></i><i></i><i></i><i></i></div>
        <div class="ph-lines is-quote"><i></i><i></i><i></i></div>
      </div>
    </div>
    <div class="reveal">
      <span class="eyebrow"><?= t('expect.eyebrow') ?></span>
      <h2><?= t('expect.title') ?></h2>
      <p class="muted"><?= t('expect.lead') ?></p>
      <div class="features">
        <div class="feature">
          <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg></div>
          <h3><?= t('expect.f1_t') ?></h3><p><?= t('expect.f1_d') ?></p>
        </div>
        <div class="feature">
          <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/></svg></div>
          <h3><?= t('expect.f2_t') ?></h3><p><?= t('expect.f2_d') ?></p>
        </div>
        <div class="feature">
          <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20 12 4l9 16z"/><path d="M12 4v16M9 20l3-6 3 6"/></svg></div>
          <h3><?= t('expect.f3_t') ?></h3><p><?= t('expect.f3_d') ?></p>
        </div>
        <div class="feature">
          <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/></svg></div>
          <h3><?= t('expect.f4_t') ?></h3><p><?= t('expect.f4_d') ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- RASPORED: dodati ovdje kad bude poznat raspored za 2027. (stilovi .schedule već postoje u style.css) -->

<!-- LOKACIJA -->
<section class="section" id="lokacija">
  <div class="wrap split">
    <div class="reveal">
      <span class="eyebrow"><?= t('location.eyebrow') ?></span>
      <h2><?= t('event.place') ?></h2>
      <p class="lead"><?= t('event.town') ?></p>
      <p><?= t('location.text') ?></p>
      <p><a class="btn btn-dark" href="https://maps.app.goo.gl/odYD8kSuALP2ZfG36" data-ev-href="mapUrl" target="_blank" rel="noopener"><?= t('location.map') ?></a></p>
      <p class="map-pin"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s7-7.4 7-12.5A7 7 0 0 0 5 9.5C5 14.6 12 22 12 22z"/><circle cx="12" cy="9.5" r="2.5"/></svg>Emek Beraka · <?= t('event.place') ?></p>
    </div>
    <div class="reveal">
      <span class="eyebrow"><?= t('archive.eyebrow') ?></span>
      <h2><?= t('archive.title') ?></h2>
      <div class="video-grid two" data-latest="2"></div>
      <p style="margin-top:18px"><a href="<?= u('mediji.php') ?>"><?= t('archive.all') ?></a></p>
    </div>
  </div>
</section>

<!-- KONTAKT -->
<section class="section dark" id="kontakt">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow"><?= t('contact.eyebrow') ?></span>
      <h2><?= t('contact.title') ?></h2>
    </div>
    <div class="contact-grid reveal">
      <div class="contact-card"><h3><?= t('contact.email') ?></h3><p><a href="mailto:cuvar.brata.svoga@gmail.com">cuvar.brata.svoga@gmail.com</a></p></div>
      <div class="contact-card"><h3><?= t('contact.fb') ?></h3><p><a href="<?= $fb ?>" target="_blank" rel="noopener"><?= t('site.name') ?> ↗</a></p></div>
      <div class="contact-card"><h3><?= t('contact.country_t') ?></h3><p><?= t('contact.country_d') ?> <?= str_replace('href="#uplate"', 'href="' . u('prijava.php', '#uplate') . '"', t('contact.country_more')) ?></p></div>
    </div>
    <p class="reveal" style="margin-top:28px"><a class="btn btn-gold" href="<?= u('kontakt.php') ?>"><?= t('contact.write_btn') ?> →</a></p>
  </div>
</section>

</main>
<?php page_close();
