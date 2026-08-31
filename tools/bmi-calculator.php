<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('BMI Calculator', 'Calculate your Body Mass Index and see your healthy weight range.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/">Home</a> / <a href="<?= e($base) ?>/tools/index.php">Tools</a> / <span>BMI Calculator</span></div>
    <span class="eyebrow"><?= icon('user') ?> Tool</span>
    <h1 class="section-title">BMI Calculator</h1>
    <p class="section-desc">Calculate your Body Mass Index (BMI) from your height and weight.</p>
  </div>
</div>

<section class="section tool-panel" style="padding-top:0;">
  <div class="container">
    <div class="card">
      <div class="tool-grid-2">
        <div class="form-group">
          <label for="bmiHeight">Height (cm)</label>
          <input type="number" id="bmiHeight" class="form-control" placeholder="e.g. 170">
        </div>
        <div class="form-group">
          <label for="bmiWeight">Weight (kg)</label>
          <input type="number" id="bmiWeight" class="form-control" placeholder="e.g. 65">
        </div>
      </div>
      <button class="btn btn-primary btn-block" id="bmiBtn"><?= icon('user') ?> Calculate BMI</button>
      <div class="tool-output big" id="bmiResult" style="display:none;"></div>
      <p style="text-align:center;color:var(--text-muted);font-size:.85rem;margin-top:14px;">
        Underweight &lt;18.5 &nbsp;·&nbsp; Normal 18.5–24.9 &nbsp;·&nbsp; Overweight 25–29.9 &nbsp;·&nbsp; Obese 30+
      </p>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/tools/bmi-calculator.js')) ?>"></script>
