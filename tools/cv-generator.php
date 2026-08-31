<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('CV / Resume Generator', 'Build a professional, animated resume live in your browser and download it as a PDF — free, no sign-up.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';

$accentColors = [
    ['key' => 'green',  'c1' => '#16a34a', 'c2' => '#22c55e'],
    ['key' => 'blue',   'c1' => '#1d4ed8', 'c2' => '#3b82f6'],
    ['key' => 'purple', 'c1' => '#7c3aed', 'c2' => '#a855f7'],
    ['key' => 'amber',  'c1' => '#b45309', 'c2' => '#f59e0b'],
    ['key' => 'rose',   'c1' => '#be123c', 'c2' => '#f43f5e'],
    ['key' => 'slate',  'c1' => '#334155', 'c2' => '#64748b'],
];
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <a href="<?= e($base) ?>/tools/index.php"><?= e(t('nav.tools')) ?></a> / <span><?= e(t('tool.cvgen.title')) ?></span></div>
    <span class="eyebrow"><?= icon('briefcase') ?> <?= e(t('tools.tag')) ?></span>
    <h1 class="section-title"><?= e(t('tool.cvgen.title')) ?></h1>
    <p class="section-desc"><?= e(t('tool.cvgen.sub_desc')) ?></p>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">

    <div class="card cvb-toolbar">
      <div class="cvb-toolbar-left">
        <div class="cvb-swatches" id="cvSwatches">
          <?php foreach ($accentColors as $i => $c): ?>
            <button type="button" class="cvb-swatch<?= $i === 0 ? ' active' : '' ?>" data-key="<?= e($c['key']) ?>" data-c1="<?= e($c['c1']) ?>" data-c2="<?= e($c['c2']) ?>"
              style="background:linear-gradient(135deg,<?= e($c['c1']) ?>,<?= e($c['c2']) ?>);color:<?= e($c['c1']) ?>;"
              aria-label="<?= e($c['key']) ?>" title="<?= e(t('tool.cvgen.theme_color')) ?>"></button>
          <?php endforeach; ?>
        </div>
        <div class="cvb-privacy"><?= icon('check') ?> <?= e(t('tool.cvgen.privacy_note')) ?></div>
      </div>
      <div class="cvb-toolbar-actions">
        <button type="button" class="btn btn-ghost btn-sm" id="cvResetBtn"><?= e(t('tool.cvgen.reset')) ?></button>
        <button type="button" class="btn btn-primary" id="cvDownloadBtn"><?= icon('download') ?> <?= e(t('tool.cvgen.download')) ?></button>
      </div>
    </div>

    <div class="cvb-grid">
      <!-- ===== Form ===== -->
      <div class="cvb-form">

        <div class="card cvb-section">
          <h3><?= icon('user') ?> <?= e(t('tool.cvgen.section_personal')) ?></h3>

          <div class="cvb-photo-row">
            <img id="cvPhotoImg" class="cvb-photo-preview" style="display:none;" alt="">
            <div id="cvPhotoPlaceholder" class="cvb-photo-placeholder"><?= icon('camera') ?></div>
            <div class="cvb-photo-actions">
              <label class="btn btn-outline btn-sm" for="cvPhotoInput" style="cursor:pointer;"><?= icon('camera') ?> <?= e(t('tool.cvgen.photo_upload')) ?></label>
              <input type="file" id="cvPhotoInput" class="cvb-file-input" accept="image/*">
              <button type="button" class="btn btn-ghost btn-sm" id="cvPhotoRemoveBtn" style="display:none;"><?= e(t('tool.cvgen.photo_remove')) ?></button>
            </div>
          </div>

          <div class="form-group">
            <label for="cvName"><?= e(t('tool.cvgen.full_name')) ?></label>
            <input type="text" id="cvName" class="form-control" maxlength="80">
          </div>
          <div class="form-group">
            <label for="cvTitle"><?= e(t('tool.cvgen.job_title')) ?></label>
            <input type="text" id="cvTitle" class="form-control" maxlength="100">
          </div>
          <div class="cvb-row2">
            <div class="form-group">
              <label for="cvEmail"><?= e(t('tool.cvgen.email')) ?></label>
              <input type="email" id="cvEmail" class="form-control" maxlength="100">
            </div>
            <div class="form-group">
              <label for="cvPhone"><?= e(t('tool.cvgen.phone')) ?></label>
              <input type="text" id="cvPhone" class="form-control" maxlength="40">
            </div>
          </div>
          <div class="form-group">
            <label for="cvAddress"><?= e(t('tool.cvgen.address')) ?></label>
            <input type="text" id="cvAddress" class="form-control" maxlength="120">
          </div>
          <div class="form-group">
            <label for="cvSummary"><?= e(t('tool.cvgen.summary')) ?></label>
            <textarea id="cvSummary" class="form-control" style="min-height:100px;" maxlength="600"></textarea>
          </div>
        </div>

        <div class="card cvb-section">
          <h3><?= icon('briefcase') ?> <?= e(t('tool.cvgen.section_experience')) ?></h3>
          <div id="expList"></div>
          <button type="button" class="cvb-add-btn" id="addExpBtn"><?= icon('plus') ?> <?= e(t('tool.cvgen.add_experience')) ?></button>
        </div>

        <div class="card cvb-section">
          <h3><?= icon('graduation-cap') ?> <?= e(t('tool.cvgen.section_education')) ?></h3>
          <div id="eduList"></div>
          <button type="button" class="cvb-add-btn" id="addEduBtn"><?= icon('plus') ?> <?= e(t('tool.cvgen.add_education')) ?></button>
        </div>

        <div class="card cvb-section">
          <h3><?= icon('sparkle') ?> <?= e(t('tool.cvgen.section_skills')) ?></h3>
          <div id="skillList"></div>
          <button type="button" class="cvb-add-btn" id="addSkillBtn"><?= icon('plus') ?> <?= e(t('tool.cvgen.add_skill')) ?></button>
        </div>

        <div class="card cvb-section">
          <h3><?= icon('award') ?> <?= e(t('tool.cvgen.section_certificates')) ?></h3>
          <div id="certList"></div>
          <button type="button" class="cvb-add-btn" id="addCertBtn"><?= icon('plus') ?> <?= e(t('tool.cvgen.add_cert')) ?></button>
        </div>

      </div>

      <!-- ===== Live Preview ===== -->
      <div class="cvb-preview-pane">
        <div class="cvb-preview-sticky">
          <div id="cvPreview" class="cv-doc"></div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Row templates used by JS (kept out of the tab order / not submitted) -->
<template id="tpl-exp-row">
  <div class="cvb-entry" data-role="exp-row">
    <button type="button" class="cvb-entry-remove" data-action="remove"><?= icon('trash') ?></button>
    <div class="form-group"><label><?= e(t('tool.cvgen.position')) ?></label><input type="text" class="form-control" data-field="position" maxlength="100"></div>
    <div class="form-group"><label><?= e(t('tool.cvgen.company')) ?></label><input type="text" class="form-control" data-field="company" maxlength="100"></div>
    <div class="cvb-row2">
      <div class="form-group"><label><?= e(t('tool.cvgen.start')) ?></label><input type="text" class="form-control" data-field="start" maxlength="20" placeholder="2023"></div>
      <div class="form-group"><label><?= e(t('tool.cvgen.end')) ?></label><input type="text" class="form-control" data-field="end" maxlength="20" placeholder="<?= e(t('tool.cvgen.current')) ?>"></div>
    </div>
    <div class="form-group"><label><?= e(t('tool.cvgen.description')) ?></label><textarea class="form-control" data-field="description" style="min-height:70px;" maxlength="400"></textarea></div>
  </div>
</template>

<template id="tpl-edu-row">
  <div class="cvb-entry" data-role="edu-row">
    <button type="button" class="cvb-entry-remove" data-action="remove"><?= icon('trash') ?></button>
    <div class="form-group"><label><?= e(t('tool.cvgen.degree')) ?></label><input type="text" class="form-control" data-field="degree" maxlength="120"></div>
    <div class="form-group"><label><?= e(t('tool.cvgen.institution')) ?></label><input type="text" class="form-control" data-field="institution" maxlength="120"></div>
    <div class="cvb-row2">
      <div class="form-group"><label><?= e(t('tool.cvgen.start')) ?></label><input type="text" class="form-control" data-field="start" maxlength="20" placeholder="2019"></div>
      <div class="form-group"><label><?= e(t('tool.cvgen.end')) ?></label><input type="text" class="form-control" data-field="end" maxlength="20" placeholder="2023"></div>
    </div>
    <div class="form-group"><label><?= e(t('tool.cvgen.description')) ?></label><textarea class="form-control" data-field="description" style="min-height:60px;" maxlength="300"></textarea></div>
  </div>
</template>

<template id="tpl-skill-row">
  <div class="cvb-entry" data-role="skill-row" style="padding-bottom:16px;">
    <button type="button" class="cvb-entry-remove" data-action="remove"><?= icon('trash') ?></button>
    <div class="cvb-skill-row">
      <input type="text" class="form-control" data-field="name" maxlength="40" placeholder="<?= e(t('tool.cvgen.skill_name')) ?>">
      <input type="range" min="10" max="100" step="5" data-field="level">
      <span class="cvb-skill-pct" data-role="pct">80%</span>
    </div>
  </div>
</template>

<template id="tpl-cert-row">
  <div class="cvb-entry" data-role="cert-row">
    <button type="button" class="cvb-entry-remove" data-action="remove"><?= icon('trash') ?></button>
    <div class="form-group"><label><?= e(t('tool.cvgen.cert_title')) ?></label><input type="text" class="form-control" data-field="title" maxlength="120"></div>
    <div class="cvb-row2">
      <div class="form-group"><label><?= e(t('tool.cvgen.issuer')) ?></label><input type="text" class="form-control" data-field="issuer" maxlength="100"></div>
      <div class="form-group"><label><?= e(t('tool.cvgen.year')) ?></label><input type="text" class="form-control" data-field="year" maxlength="20"></div>
    </div>
  </div>
</template>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/tools/cv-generator.js')) ?>"></script>
