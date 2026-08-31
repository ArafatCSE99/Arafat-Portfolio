<?php
require_once __DIR__ . '/../includes/functions.php';

$meta = seo_meta('Free Online Tools', 'A collection of free, fast, browser-based utility tools — no sign-up required.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');

$tools = [
    ['slug' => 'cv-generator', 'key' => 'cvgen', 'icon' => 'briefcase'],
    ['slug' => 'age-calculator', 'key' => 'age', 'icon' => 'calendar'],
    ['slug' => 'percentage-calculator', 'key' => 'percentage', 'icon' => 'code'],
    ['slug' => 'word-counter', 'key' => 'word', 'icon' => 'edit'],
    ['slug' => 'qr-generator', 'key' => 'qr', 'icon' => 'search'],
    ['slug' => 'password-generator', 'key' => 'password', 'icon' => 'settings'],
    ['slug' => 'bmi-calculator', 'key' => 'bmi', 'icon' => 'user'],
    ['slug' => 'unit-converter', 'key' => 'unit', 'icon' => 'arrow-right'],
    ['slug' => 'color-picker', 'key' => 'color', 'icon' => 'palette'],
    ['slug' => 'json-formatter', 'key' => 'json', 'icon' => 'code'],
];

require __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <span><?= e(t('nav.tools')) ?></span></div>
    <span class="eyebrow"><?= icon('sparkle') ?> <?= e(t('tools.eyebrow')) ?></span>
    <h1 class="section-title"><?= e(t('tools.heading')) ?></h1>
    <p class="section-desc"><?= e(t('tools.desc')) ?></p>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">
    <div class="grid grid-3">
      <?php foreach ($tools as $tool): ?>
        <a href="<?= e($base) ?>/tools/<?= e($tool['slug']) ?>.php" class="card tool-card reveal">
          <div class="service-icon"><?= icon($tool['icon']) ?></div>
          <h3 class="project-title"><?= e(t('tool.' . $tool['key'] . '.title')) ?></h3>
          <p class="project-desc"><?= e(t('tool.' . $tool['key'] . '.desc')) ?></p>
          <span class="read-more"><?= e(t('common.open_tool')) ?> <?= icon('arrow-right') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
