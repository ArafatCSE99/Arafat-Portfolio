<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('Unit Converter', 'Convert between length, weight, and temperature units instantly.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/">Home</a> / <a href="<?= e($base) ?>/tools/index.php">Tools</a> / <span>Unit Converter</span></div>
    <span class="eyebrow"><?= icon('arrow-right') ?> Tool</span>
    <h1 class="section-title">Unit Converter</h1>
    <p class="section-desc">Convert between common length, weight, and temperature units.</p>
  </div>
</div>

<section class="section tool-panel" style="padding-top:0;">
  <div class="container">
    <div class="card">
      <div class="form-group">
        <label for="ucCategory">Category</label>
        <select id="ucCategory" class="form-control">
          <option value="length">Length</option>
          <option value="weight">Weight</option>
          <option value="temperature">Temperature</option>
        </select>
      </div>

      <div class="tool-grid-2">
        <div class="form-group">
          <label for="ucFrom">From</label>
          <select id="ucFrom" class="form-control"></select>
        </div>
        <div class="form-group">
          <label for="ucTo">To</label>
          <select id="ucTo" class="form-control"></select>
        </div>
      </div>

      <div class="form-group">
        <label for="ucValue">Value</label>
        <input type="number" id="ucValue" class="form-control" value="1">
      </div>

      <div class="tool-output big" id="ucResult">—</div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/tools/unit-converter.js')) ?>"></script>
