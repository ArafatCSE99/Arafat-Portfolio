<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('Password Generator', 'Generate strong, random, and secure passwords instantly.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/">Home</a> / <a href="<?= e($base) ?>/tools/index.php">Tools</a> / <span>Password Generator</span></div>
    <span class="eyebrow"><?= icon('settings') ?> Tool</span>
    <h1 class="section-title">Password Generator</h1>
    <p class="section-desc">Create a strong, random password with the options below.</p>
  </div>
</div>

<section class="section tool-panel" style="padding-top:0;">
  <div class="container">
    <div class="card">
      <div class="copy-row" style="margin-bottom:20px;">
        <input type="text" id="pwOutput" class="form-control" readonly style="font-family:Consolas,monospace;font-size:1.1rem;">
        <button class="btn btn-outline" id="pwCopy" type="button"><?= icon('check') ?> Copy</button>
      </div>
      <div class="strength-meter"><div class="strength-fill" id="pwStrength"></div></div>
      <p style="font-size:.82rem;color:var(--text-muted);margin-top:6px;" id="pwStrengthLabel">Strength: —</p>

      <div style="margin-top:24px;">
        <div class="range-row">
          <label style="min-width:110px;">Length</label>
          <input type="range" id="pwLength" min="6" max="32" value="16">
          <strong id="pwLengthVal">16</strong>
        </div>
      </div>

      <div style="margin-top:20px;">
        <label class="check-row"><input type="checkbox" id="pwUpper" checked> Uppercase Letters (A-Z)</label>
        <label class="check-row"><input type="checkbox" id="pwLower" checked> Lowercase Letters (a-z)</label>
        <label class="check-row"><input type="checkbox" id="pwNumbers" checked> Numbers (0-9)</label>
        <label class="check-row"><input type="checkbox" id="pwSymbols" checked> Symbols (!@#$...)</label>
      </div>

      <button class="btn btn-primary btn-block" id="pwGenBtn" style="margin-top:8px;"><?= icon('settings') ?> Generate Password</button>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/tools/password-generator.js')) ?>"></script>
