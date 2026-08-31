<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('Percentage Calculator', 'Calculate percentages, percentage of a value, and percentage change.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <a href="<?= e($base) ?>/tools/index.php"><?= e(t('nav.tools')) ?></a> / <span><?= e(t('tool.percentage.title')) ?></span></div>
    <span class="eyebrow"><?= icon('code') ?> <?= e(t('tools.tag')) ?></span>
    <h1 class="section-title"><?= e(t('tool.percentage.title')) ?></h1>
    <p class="section-desc"><?= e(t('tool.percentage.sub_desc')) ?></p>
  </div>
</div>

<section class="section tool-panel" style="padding-top:0;max-width:760px;">
  <div class="container">

    <div class="card" style="margin-bottom:24px;">
      <h3 style="margin-bottom:16px;"><?= e(t('tool.percentage.q1')) ?></h3>
      <div class="tool-grid-2">
        <div class="form-group"><label>X (%)</label><input type="number" class="form-control" id="p1x"></div>
        <div class="form-group"><label>Y</label><input type="number" class="form-control" id="p1y"></div>
      </div>
      <button class="btn btn-primary btn-block" onclick="calcP1()"><?= icon('code') ?> <?= e(t('tool.percentage.calc')) ?></button>
      <div class="tool-output" id="p1Result" style="display:none;"></div>
    </div>

    <div class="card" style="margin-bottom:24px;">
      <h3 style="margin-bottom:16px;"><?= e(t('tool.percentage.q2')) ?></h3>
      <div class="tool-grid-2">
        <div class="form-group"><label>X</label><input type="number" class="form-control" id="p2x"></div>
        <div class="form-group"><label>Y</label><input type="number" class="form-control" id="p2y"></div>
      </div>
      <button class="btn btn-primary btn-block" onclick="calcP2()"><?= icon('code') ?> <?= e(t('tool.percentage.calc')) ?></button>
      <div class="tool-output" id="p2Result" style="display:none;"></div>
    </div>

    <div class="card">
      <h3 style="margin-bottom:16px;"><?= e(t('tool.percentage.q3')) ?></h3>
      <div class="tool-grid-2">
        <div class="form-group"><label><?= e(t('tool.percentage.from')) ?></label><input type="number" class="form-control" id="p3x"></div>
        <div class="form-group"><label><?= e(t('tool.percentage.to')) ?></label><input type="number" class="form-control" id="p3y"></div>
      </div>
      <button class="btn btn-primary btn-block" onclick="calcP3()"><?= icon('code') ?> <?= e(t('tool.percentage.calc')) ?></button>
      <div class="tool-output" id="p3Result" style="display:none;"></div>
    </div>

  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/tools/percentage-calculator.js')) ?>"></script>
