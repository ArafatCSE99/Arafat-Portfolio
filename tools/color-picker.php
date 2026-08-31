<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('Color Picker', 'Pick a color and instantly get its HEX, RGB, and HSL values.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/">Home</a> / <a href="<?= e($base) ?>/tools/index.php">Tools</a> / <span>Color Picker</span></div>
    <span class="eyebrow"><?= icon('palette') ?> Tool</span>
    <h1 class="section-title">Color Picker</h1>
    <p class="section-desc">Pick a color to get its HEX, RGB, and HSL values.</p>
  </div>
</div>

<section class="section tool-panel" style="padding-top:0;">
  <div class="container">
    <div class="card">
      <div id="cpSwatch" class="color-swatch"></div>
      <div class="form-group">
        <label for="cpInput">Pick a Color</label>
        <input type="color" id="cpInput" value="#7c3aed" style="width:100%;height:52px;border-radius:var(--radius-sm);border:1.5px solid var(--border);cursor:pointer;background:none;">
      </div>

      <div class="form-group">
        <label>HEX</label>
        <div class="copy-row"><input type="text" id="cpHex" class="form-control" readonly><button class="btn btn-outline btn-sm copy-btn" data-target="cpHex" type="button"><?= icon('check') ?></button></div>
      </div>
      <div class="form-group">
        <label>RGB</label>
        <div class="copy-row"><input type="text" id="cpRgb" class="form-control" readonly><button class="btn btn-outline btn-sm copy-btn" data-target="cpRgb" type="button"><?= icon('check') ?></button></div>
      </div>
      <div class="form-group">
        <label>HSL</label>
        <div class="copy-row"><input type="text" id="cpHsl" class="form-control" readonly><button class="btn btn-outline btn-sm copy-btn" data-target="cpHsl" type="button"><?= icon('check') ?></button></div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/tools/color-picker.js')) ?>"></script>
