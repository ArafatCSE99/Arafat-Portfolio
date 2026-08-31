<?php
require_once __DIR__ . '/includes/functions.php';

$meta = seo_meta('Services', 'Services I offer including web development, UI/UX design, and more.');
$activePage = 'services';
$base = rtrim(SITE_URL, '/');

$services = db()->query('SELECT * FROM services ORDER BY sort_order')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <span><?= e(t('nav.services')) ?></span></div>
    <span class="eyebrow"><?= e(t('home.services_eyebrow')) ?></span>
    <h1 class="section-title"><?= e(t('services.heading')) ?></h1>
    <p class="section-desc"><?= e(t('services.desc')) ?></p>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">
    <div class="grid grid-4">
      <?php foreach ($services as $s): ?>
        <div class="card service-card reveal">
          <?php if (!empty($s['image'])): ?>
            <div class="service-thumb"><img src="<?= e($base) ?>/<?= e($s['image']) ?>" alt="<?= e(tf($s, 'title')) ?>"></div>
          <?php else: ?>
            <div class="service-icon" style="margin:24px 24px 0;"><?= icon($s['icon_key']) ?></div>
          <?php endif; ?>
          <div class="service-card-body">
            <h3><?= e(tf($s, 'title')) ?></h3>
            <p><?= e(tf($s, 'description')) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="card reveal" style="padding:56px;text-align:center;background:var(--gradient-brand);color:#fff;border:none;margin-top:56px;">
      <h2 style="font-size:1.6rem;margin-bottom:14px;"><?= e(t('services.custom_title')) ?></h2>
      <p style="opacity:.9;max-width:520px;margin:0 auto 28px;"><?= e(t('services.custom_desc')) ?></p>
      <a href="<?= e($base) ?>/contact.php" class="btn" style="background:#fff;color:var(--primary);"><?= icon('send') ?> <?= e(t('services.custom_btn')) ?></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
