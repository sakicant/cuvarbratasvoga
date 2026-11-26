<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/layout.php';
page_open('prijava.php', t('prij.title'), t('prij.description'));

/* Prijave otvorene? Uključuje se u api/config.php ('registration_open' => true).
   ?prijave=1 prikazuje stranicu kao da su otvorene (samo za pregled – poslužitelj i dalje odbija prijave dok nisu otvorene). */
$open = cbs_registration_open() || (!defined('CBS_STATIC') && isset($_GET['prijave']));
?>
<main id="main">

<section class="page-hero">
  <div class="wrap">
    <span class="eyebrow"><?= t('prij.eyebrow') ?></span>
    <h1><?= t('prij.h1') ?></h1>
    <p class="lead"><?= t('prij.lead') ?></p>
    <?php if (!$open): ?>
    <div class="reg-closed" role="status">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      <div><strong><?= t('prij.closed_title') ?></strong><p><?= t('prij.closed_text') ?></p></div>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- KOTIZACIJA -->
<section class="section bone" id="kotizacija">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow"><?= t('fees.eyebrow') ?></span>
      <h2><?= t('fees.title') ?></h2>
      <p class="lead"><?= t('fees.lead') ?></p>
    </div>
    <div class="prices reveal">
      <div class="price accent"><h3><?= t('fees.fee_t') ?></h3><b>80<small>€</small></b><p><?= t('fees.fee_d') ?></p></div>
      <div class="price"><h3><?= t('fees.acc_t') ?></h3><b>40<small>€</small></b><p><?= t('fees.acc_d') ?></p></div>
      <div class="price"><h3><?= t('fees.dep_t') ?></h3><b>20<small>€</small></b><p><?= t('fees.dep_d') ?></p></div>
      <div class="price"><h3><?= t('fees.son_t') ?></h3><b>−50<small>%</small></b><p><?= t('fees.son_d') ?></p></div>
    </div>
    <ol class="steps reveal">
      <li><h3><?= t('steps.s1_t') ?></h3><p><?= t('steps.s1_d') ?></p></li>
      <li><h3><?= t('steps.s2_t') ?></h3><p><?= t('steps.s2_d') ?></p></li>
      <li><h3><?= t('steps.s3_t') ?></h3><p><?= t('steps.s3_d') ?></p></li>
    </ol>

    <!-- OBRAZAC ZA PRIJAVU -->
    <div class="form-card<?= $open ? '' : ' is-locked' ?>" id="obrazac">
      <form id="signup-form" novalidate>
        <h3 class="form-title"><?= t('form.title') ?> <span data-ev="year">2027</span></h3>
        <?php if (!$open): ?><p class="form-lock-note"><?= t('prij.closed_title') ?></p><?php endif; ?>
        <p class="muted form-sub"><?= t('form.sub') ?></p>

        <fieldset class="form-fields"<?= $open ? '' : ' disabled' ?>>
        <div class="fgrid">
          <div class="field"><label for="f-first"><?= t('form.first') ?> *</label><input id="f-first" name="first_name" autocomplete="given-name" required maxlength="80"></div>
          <div class="field"><label for="f-last"><?= t('form.last') ?> *</label><input id="f-last" name="last_name" autocomplete="family-name" required maxlength="80"></div>
          <div class="field"><label for="f-email"><?= t('form.email') ?> *</label><input id="f-email" name="email" type="email" autocomplete="email" inputmode="email" required maxlength="160"><small><?= t('form.email_hint') ?></small></div>
          <div class="field"><label for="f-phone"><?= t('form.phone') ?> *</label><input id="f-phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" required maxlength="40" placeholder="+3…"></div>
          <div class="field"><label for="f-birth"><?= t('form.birth') ?> *</label><input id="f-birth" name="birth_date" type="date" required min="1920-01-01"></div>
          <div class="field"><label for="f-country"><?= t('form.country') ?> *</label>
            <select id="f-country" name="country" required>
              <option value=""><?= t('form.choose') ?></option>
              <?php foreach (['HR', 'BA', 'RS', 'ME', 'MK', 'SI', 'XX'] as $c): ?>
              <option value="<?= $c ?>"><?= t('country.' . $c) ?></option>
              <?php endforeach; ?>
            </select></div>
          <div class="field"><label for="f-city"><?= t('form.city') ?> *</label><input id="f-city" name="city" autocomplete="address-level2" required maxlength="80"></div>
          <div class="field"><label for="f-address"><?= t('form.address') ?> *</label><input id="f-address" name="address" autocomplete="street-address" required maxlength="160"></div>
          <div class="field full"><label for="f-church"><?= t('form.church') ?></label><input id="f-church" name="church" maxlength="120" placeholder="<?= h_(t('form.church_ph')) ?>"></div>

          <fieldset class="field full" id="f-accommodation">
            <legend><?= t('form.accommodation') ?> *</legend>
            <div class="choices">
              <label class="choice"><input type="radio" name="accommodation" value="need_tent" required><span><b><?= t('form.acc_need_t') ?></b><small><?= t('form.acc_need_d') ?></small></span></label>
              <label class="choice"><input type="radio" name="accommodation" value="own_tent"><span><b><?= t('form.acc_own_t') ?></b><small><?= t('form.acc_own_d') ?></small></span></label>
              <label class="choice"><input type="radio" name="accommodation" value="own_arrangement"><span><b><?= t('form.acc_arr_t') ?></b><small><?= t('form.acc_arr_d') ?></small></span></label>
            </div>
          </fieldset>

          <fieldset class="field full" id="f-tshirt">
            <legend><?= t('form.tshirt') ?> *</legend>
            <div class="sizes">
              <?php foreach (['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $i => $s): ?>
              <label><input type="radio" name="tshirt" value="<?= $s ?>"<?= $i === 0 ? ' required' : '' ?>><span><?= $s ?></span></label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <div class="field full"><label for="f-notes"><?= t('form.notes') ?></label><textarea id="f-notes" name="notes" rows="3" maxlength="1000" placeholder="<?= h_(t('form.notes_ph')) ?>"></textarea></div>

          <div class="hp" aria-hidden="true"><label><?= t('form.website') ?> <input name="website" tabindex="-1" autocomplete="off"></label></div>

          <div class="field full" id="f-consent">
            <label class="check"><input type="checkbox" name="consent" value="1" required>
              <span><?= t('form.consent', ['privacy' => u('privatnost.php')]) ?> *</span></label>
          </div>
        </div>

        <div class="form-error" role="alert" hidden></div>
        <button class="btn btn-gold btn-submit" type="submit"><?= $open ? t('form.submit') : t('prij.closed_btn') ?></button>
        </fieldset>
      </form>

      <div class="form-done" id="signup-done" hidden tabindex="-1">
        <div class="done-icon" aria-hidden="true">✓</div>
        <h3><?= t('done.title') ?></h3>
        <p><?= t('done.ref') ?> <strong class="done-ref"></strong></p>
        <p class="done-mail"><?= t('done.mail') ?></p>
        <div class="done-pay">
          <h4><?= t('done.pay_title') ?></h4>
          <p><?= t('done.pay_text') ?></p>
          <dl class="done-pay-rows"></dl>
        </div>
        <button class="btn btn-dark" type="button" id="signup-again"><?= t('done.again') ?></button>
      </div>
    </div>
  </div>
</section>

<!-- UPLATE -->
<section class="section" id="uplate">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow"><?= t('pay.eyebrow') ?></span>
      <h2><?= t('pay.title') ?></h2>
      <p class="lead"><?= t('pay.lead') ?></p>
    </div>
    <div id="payments" class="reveal"></div>
  </div>
</section>

</main>
<?php page_close(['assets/js/signup.js']);
