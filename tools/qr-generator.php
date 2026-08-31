<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('QR Code Generator', 'Generate and download a QR code from any text or URL, right in your browser.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/">Home</a> / <a href="<?= e($base) ?>/tools/index.php">Tools</a> / <span>QR Code Generator</span></div>
    <span class="eyebrow"><?= icon('search') ?> Tool</span>
    <h1 class="section-title">QR Code Generator</h1>
    <p class="section-desc">Enter any text or URL to instantly generate a downloadable QR code.</p>
  </div>
</div>

<section class="section tool-panel" style="padding-top:0;">
  <div class="container">
    <div class="card">
      <div class="form-group">
        <label for="qrText">Text or URL</label>
        <input type="text" id="qrText" class="form-control" placeholder="https://example.com" value="https://arafat.dev">
      </div>
      <button class="btn btn-primary btn-block" id="qrGenBtn"><?= icon('search') ?> Generate QR Code</button>
      <div style="text-align:center;margin-top:24px;">
        <div id="qrOutput" style="display:inline-block;padding:16px;background:#fff;border-radius:var(--radius-md);"></div>
      </div>
      <a href="#" id="qrDownload" class="btn btn-outline btn-block" style="margin-top:16px;display:none;" download="qrcode.png"><?= icon('download') ?> Download PNG</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/vendor/qrcode.js')) ?>"></script>
<script src="<?= e(asset_url('assets/js/tools/qr-generator.js')) ?>"></script>
