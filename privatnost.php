<?php
declare(strict_types=1);
require __DIR__ . '/inc/layout.php';
page_open('privatnost.php', t('privacy.title'), t('privacy.what_p'), ['solid_header' => true, 'noindex' => true]);
?>
<main id="main" class="section" style="padding-top:150px">
  <div class="wrap prose" style="max-width:760px">
    <span class="eyebrow"><?= t('privacy.eyebrow') ?></span>
    <h1 style="font-size:clamp(2.4rem,6vw,3.6rem)"><?= t('privacy.h1') ?></h1>
    <?php foreach (['what', 'why', 'who', 'long', 'rights'] as $k): ?>
    <h3><?= t("privacy.{$k}_h") ?></h3>
    <p><?= t("privacy.{$k}_p") ?></p>
    <?php endforeach; ?>
    <p style="margin-top:40px"><a class="btn btn-dark" href="<?= u('index.php') ?>"><?= t('privacy.back') ?></a></p>
  </div>
</main>
<?php page_close();
