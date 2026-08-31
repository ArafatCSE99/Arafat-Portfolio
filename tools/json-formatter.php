<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('JSON Formatter', 'Format, validate, and beautify raw JSON data instantly.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/">Home</a> / <a href="<?= e($base) ?>/tools/index.php">Tools</a> / <span>JSON Formatter</span></div>
    <span class="eyebrow"><?= icon('code') ?> Tool</span>
    <h1 class="section-title">JSON Formatter</h1>
    <p class="section-desc">Paste raw JSON below to validate and beautify it.</p>
  </div>
</div>

<section class="section tool-panel" style="padding-top:0;max-width:820px;">
  <div class="container">
    <div class="card">
      <div class="form-group">
        <label for="jsonInput">Raw JSON</label>
        <textarea id="jsonInput" class="form-control" style="min-height:200px;font-family:Consolas,monospace;font-size:.88rem;" placeholder='{"name": "Arafat", "role": "Developer"}'></textarea>
      </div>
      <div class="btn-row">
        <button class="btn btn-primary" id="jsonFormatBtn"><?= icon('code') ?> Format &amp; Validate</button>
        <button class="btn btn-outline" id="jsonMinifyBtn" type="button">Minify</button>
        <button class="btn btn-ghost" id="jsonClearBtn" type="button">Clear</button>
      </div>
      <div id="jsonAlert"></div>
      <pre class="code-block" id="jsonOutput" style="display:none;margin-top:18px;"></pre>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/tools/json-formatter.js')) ?>"></script>
