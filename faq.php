<?php
require_once __DIR__ . '/includes/functions.php';

$meta = seo_meta('FAQ', 'Frequently asked questions about my services and how I work.');
$activePage = 'faq';
$base = rtrim(SITE_URL, '/');

$faqs = db()->query('SELECT * FROM faqs ORDER BY sort_order, id')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <span><?= e(t('nav.faq')) ?></span></div>
    <span class="eyebrow"><?= e(t('faq.eyebrow')) ?></span>
    <h1 class="section-title"><?= e(t('faq.heading')) ?></h1>
    <p class="section-desc"><?= e(t('faq.desc_prefix')) ?> <a href="<?= e($base) ?>/contact.php" style="color:var(--primary);font-weight:600;"><?= e(t('faq.contact_link')) ?></a>.</p>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:760px;">
    <?php if (!$faqs): ?>
      <div class="empty-state"><?= icon('search') ?><p><?= e(t('faq.empty')) ?></p></div>
    <?php else: ?>
      <div class="faq-list">
        <?php foreach ($faqs as $i => $f): ?>
          <div class="faq-item reveal">
            <button class="faq-question" type="button" aria-expanded="false">
              <span><?= e(tf($f, 'question')) ?></span>
              <?= icon('arrow-right', 'icon faq-caret') ?>
            </button>
            <div class="faq-answer"><p><?= e(tf($f, 'answer')) ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
