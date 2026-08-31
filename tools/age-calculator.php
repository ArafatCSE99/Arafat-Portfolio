<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('Age Calculator', 'Calculate your exact age in years, months, and days.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <a href="<?= e($base) ?>/tools/index.php"><?= e(t('nav.tools')) ?></a> / <span><?= e(t('tool.age.title')) ?></span></div>
    <span class="eyebrow"><?= icon('calendar') ?> <?= e(t('tools.tag')) ?></span>
    <h1 class="section-title"><?= e(t('tool.age.title')) ?></h1>
    <p class="section-desc"><?= e(t('tool.age.sub_desc')) ?></p>
  </div>
</div>

<section class="section tool-panel" style="padding-top:0;">
  <div class="container">
    <div class="card">
      <div class="form-group">
        <label for="dob"><?= e(t('tool.age.label')) ?></label>
        <input type="date" id="dob" class="form-control">
      </div>
      <button class="btn btn-primary btn-block" id="calcBtn"><?= icon('calendar') ?> <?= e(t('tool.age.btn')) ?></button>
      <div class="tool-output big" id="result" style="display:none;"></div>
      <div class="grid grid-3" id="resultGrid" style="display:none;margin-top:16px;text-align:center;">
        <div><div style="font-size:1.4rem;font-weight:800;" id="rYears">0</div><div style="color:var(--text-muted);font-size:.8rem;"><?= e(t('tool.age.years')) ?></div></div>
        <div><div style="font-size:1.4rem;font-weight:800;" id="rMonths">0</div><div style="color:var(--text-muted);font-size:.8rem;"><?= e(t('tool.age.months')) ?></div></div>
        <div><div style="font-size:1.4rem;font-weight:800;" id="rDays">0</div><div style="color:var(--text-muted);font-size:.8rem;"><?= e(t('tool.age.days')) ?></div></div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/tools/age-calculator.js')) ?>"></script>
