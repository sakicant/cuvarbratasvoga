<?php
declare(strict_types=1);
require __DIR__ . '/inc/layout.php';
page_open('index.php', t('home.title'), t('home.description'));
$fb = 'https://www.facebook.com/CuvarBrataSvoga/';
?>
<main id="main">

<!-- HERO -->
<section class="hero">
  <div class="wrap">
    <div>
      <div class="hero-kicker">
        <span class="chip solid">ČBS <span data-ev="year">2027</span></span>
        <span class="chip"><span data-ev="dateLabel">24. – 27. 6. 2027.</span></span>
      </div>
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
        <a class="btn btn-gold only-live only-reg" href="#prijava"><?= t('home.btn_signup') ?></a>
        <a class="btn btn-gold only-live only-reg-closed" href="<?= u('mediji.php') ?>"><?= t('home.btn_talks') ?></a>
        <a class="btn btn-gold only-past" href="<?= u('mediji.php') ?>"><?= t('home.btn_talks') ?></a>
        <a class="btn btn-ghost" href="#okupljanje"><?= t('home.btn_what') ?></a>
      </div>
    </div>
    <div class="hero-ticket" aria-hidden="true">
      <img src="assets/brand/png/cbs-logo-white-512.png" alt="" width="150" height="136">
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
      <p><em><?= t('invite.grace') ?></em></p>
      <p><?= t('invite.p1') ?></p>
      <p><?= t('invite.p2') ?></p>
      <p><?= t('invite.p3') ?></p>
      <blockquote><?= t('invite.quote') ?><cite><?= t('invite.quote_by') ?></cite></blockquote>
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

<!-- KOTIZACIJA I PRIJAVA (vidljivo samo kad su prijave otvorene) -->
<section class="section bone only-reg" id="prijava">
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

    <div class="signup reveal" style="margin-top:40px">
      <div>
        <span class="eyebrow only-live"><?= t('signup.open_eyebrow') ?></span>
        <span class="eyebrow only-past"><?= t('signup.closed_eyebrow') ?></span>
        <h2 class="only-live"><?= t('signup.open_title') ?></h2>
        <h2 class="only-past"><?= t('signup.closed_title') ?></h2>
        <p class="muted only-live"><?= t('signup.open_text') ?></p>
        <p class="muted only-past"><?= t('signup.closed_text') ?></p>
      </div>
      <div class="actions">
        <a class="btn btn-gold only-live" href="#obrazac"><?= t('signup.btn_fill') ?></a>
        <a class="btn btn-gold only-past" href="<?= $fb ?>" target="_blank" rel="noopener"><?= t('signup.btn_follow') ?></a>
        <a class="btn btn-ghost" href="<?= u('mediji.php') ?>"><?= t('signup.btn_talks') ?></a>
      </div>
    </div>

    <!-- OBRAZAC ZA PRIJAVU -->
    <div class="form-card only-live" id="obrazac">
      <form id="signup-form" novalidate>
        <h3 class="form-title"><?= t('form.title') ?> <span data-ev="year">2027</span></h3>
        <p class="muted form-sub"><?= t('form.sub') ?></p>

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
        <button class="btn btn-gold btn-submit" type="submit"><?= t('form.submit') ?></button>
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

<!-- LOKACIJA -->
<section class="section" id="lokacija">
  <div class="wrap split">
    <div class="reveal">
      <span class="eyebrow"><?= t('location.eyebrow') ?></span>
      <h2><?= t('event.place') ?></h2>
      <p class="lead"><?= t('event.town') ?></p>
      <p><?= t('location.text') ?></p>
      <p><a class="btn btn-dark" href="https://www.google.com/maps/search/?api=1&amp;query=Bora%C4%8Dko+jezero" data-ev-href="mapUrl" target="_blank" rel="noopener"><?= t('location.map') ?></a></p>
    </div>
    <div class="reveal">
      <span class="eyebrow"><?= t('archive.eyebrow') ?></span>
      <h2><?= t('archive.title') ?></h2>
      <div class="video-grid two" data-latest="2"></div>
      <p style="margin-top:18px"><a href="<?= u('mediji.php') ?>"><?= t('archive.all') ?></a></p>
    </div>
  </div>
</section>

<!-- UPLATE (vidljivo samo kad su prijave otvorene) -->
<section class="section bone only-reg" id="uplate">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow"><?= t('pay.eyebrow') ?></span>
      <h2><?= t('pay.title') ?></h2>
      <p class="lead"><?= t('pay.lead') ?></p>
    </div>
    <div id="payments" class="reveal"></div>
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
      <div class="contact-card"><h3><?= t('contact.country_t') ?></h3><p><?= t('contact.country_d') ?> <span class="only-reg"><?= t('contact.country_more') ?></span></p></div>
    </div>
  </div>
</section>

</main>
<?php page_close(['assets/js/signup.js']);
