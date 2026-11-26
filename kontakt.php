<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/layout.php';
page_open('kontakt.php', t('contact.page_title'), t('contact.page_description'));
$flags = ['HR' => 'hr', 'BA' => 'ba', 'RS' => 'rs', 'ME' => 'me', 'MK' => 'mk', 'SI' => 'si'];
?>
<main id="main">

<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow"><?= t('contact.eyebrow') ?></span>
    <h1><?= t('contact.page_h1') ?></h1>
    <p class="lead" style="max-width:64ch"><?= t('contact.page_lead') ?></p>
  </div>
</section>

<section class="section bone">
  <div class="wrap contact-layout">
    <div class="form-card" id="poruka">
      <form id="contact-form" novalidate>
        <div class="fgrid">
          <div class="field"><label for="c-name"><?= t('contact.f_name') ?> *</label><input id="c-name" name="name" autocomplete="name" required maxlength="100"></div>
          <div class="field"><label for="c-email"><?= t('form.email') ?> *</label><input id="c-email" name="email" type="email" autocomplete="email" inputmode="email" required maxlength="160"></div>
          <div class="field full"><label for="c-country"><?= t('form.country') ?> *</label>
            <select id="c-country" name="country" required>
              <option value=""><?= t('form.choose') ?></option>
              <?php foreach (['HR', 'BA', 'RS', 'ME', 'MK', 'SI', 'XX'] as $c): ?>
              <option value="<?= $c ?>"><?= t('country.' . $c) ?></option>
              <?php endforeach; ?>
            </select></div>
          <div class="field full"><label for="c-message"><?= t('contact.f_message') ?> *</label><textarea id="c-message" name="message" rows="6" required maxlength="4000" placeholder="<?= h_(t('contact.f_message_ph')) ?>"></textarea></div>
          <div class="hp" aria-hidden="true"><label><?= t('form.website') ?> <input name="website" tabindex="-1" autocomplete="off"></label></div>
          <div class="field full" id="c-consent">
            <label class="check"><input type="checkbox" name="consent" value="1" required>
              <span><?= t('contact.f_consent', ['privacy' => u('privatnost.php')]) ?> *</span></label>
          </div>
        </div>
        <div class="form-error" role="alert" hidden></div>
        <button class="btn btn-gold btn-submit" type="submit"><?= t('contact.f_submit') ?></button>
      </form>

      <div class="form-done" id="contact-done" hidden tabindex="-1">
        <div class="done-icon" aria-hidden="true">✓</div>
        <h3><?= t('contact.sent_title') ?></h3>
        <p><?= t('contact.sent_text') ?></p>
        <button class="btn btn-dark" type="button" id="contact-again"><?= t('contact.sent_again') ?></button>
      </div>
    </div>

    <aside class="contact-side">
      <h3><?= t('contact.other_title') ?></h3>
      <ul class="contact-list">
        <li><span><?= t('contact.email') ?></span><a href="mailto:cuvar.brata.svoga@gmail.com">cuvar.brata.svoga@gmail.com</a></li>
        <li><span><?= t('contact.fb') ?></span><a href="https://www.facebook.com/CuvarBrataSvoga/" target="_blank" rel="noopener"><?= t('site.name') ?> ↗</a></li>
        <li><span>YouTube</span><a href="https://www.youtube.com/@CuvarBrataSvoga" target="_blank" rel="noopener">@CuvarBrataSvoga ↗</a></li>
      </ul>
      <h3 style="margin-top:28px"><?= t('about.team_title') ?></h3>
      <ul class="team compact">
        <?php foreach (['HR' => 'Oliver Buljat', 'BA' => 'Slavko Hadžić', 'RS' => 'Miroslav Radovanović', 'ME' => 'Dragan Novaković', 'MK' => 'Zoran Kolev', 'SI' => 'Srečko Kranjc'] as $c => $name): ?>
        <li><img class="flag" src="<?= asset('assets/img/flags/' . $flags[$c] . '.svg') ?>" width="24" height="18" alt=""><div><span class="team-country"><?= t('country.' . $c) ?></span><b><?= h_($name) ?></b></div></li>
        <?php endforeach; ?>
      </ul>
    </aside>
  </div>
</section>

</main>
<?php page_close(['assets/js/contact.js']);
