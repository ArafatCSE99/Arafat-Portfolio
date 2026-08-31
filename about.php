<?php
require_once __DIR__ . '/includes/functions.php';

$meta = seo_meta('About Me', 'Learn more about my background, skills, education, and experience.');
$activePage = 'about';
$base = rtrim(SITE_URL, '/');

$skills = db()->query('SELECT * FROM skills ORDER BY sort_order')->fetchAll();
$skillGroups = [];
foreach ($skills as $s) {
    $skillGroups[$s['category']][] = $s;
}
$education = db()->query('SELECT * FROM education ORDER BY sort_order')->fetchAll();
$experience = db()->query('SELECT * FROM experience ORDER BY sort_order')->fetchAll();
$certificates = db()->query('SELECT * FROM certificates ORDER BY sort_order')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <span><?= e(t('nav.about')) ?></span></div>
    <span class="eyebrow"><?= e(t('about.eyebrow')) ?></span>
    <h1 class="section-title"><?= e(t('about.heading')) ?></h1>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container hero-grid">
    <div class="reveal about-photo-wrap">
      <img src="<?= e($base) ?>/<?= e(setting('avatar')) ?>" alt="<?= e(setting('site_title')) ?>" class="about-hero-photo">
    </div>
    <div class="reveal">
      <span class="eyebrow"><?= icon('user') ?> <?= e(t('about.who_eyebrow')) ?></span>
      <h2 class="section-title"><?= e(setting('site_title')) ?></h2>
      <p style="color:var(--text-muted);margin-bottom:20px;"><?= e(setting_t('bio_long')) ?></p>
      <div class="grid grid-2" style="margin-bottom:24px;">
        <div><strong><?= e(t('common.email')) ?>:</strong> <?= e(setting('email')) ?></div>
        <div><strong><?= e(t('common.phone')) ?>:</strong> <?= e(setting('phone')) ?></div>
        <div><strong><?= e(t('common.location')) ?>:</strong> <?= e(setting('address')) ?></div>
        <div><strong><?= e(t('common.availability')) ?>:</strong> <?= e(t('common.open_to_work')) ?></div>
      </div>
      <a href="<?= e($base) ?>/<?= e(setting('cv_path')) ?>" class="btn btn-primary" download><?= icon('download') ?> <?= e(t('home.download_cv')) ?></a>
    </div>
  </div>
</section>

<!-- Skills -->
<section class="section section--alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= icon('code') ?> <?= e(t('about.skills_eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('about.skills_title')) ?></h2>
    </div>
    <div class="grid grid-2">
      <?php foreach ($skillGroups as $category => $items): ?>
        <div class="skill-group reveal">
          <h3><?= e($category) ?></h3>
          <?php foreach ($items as $skill): ?>
            <div class="skill-item">
              <div class="skill-item-top"><span><?= e($skill['name']) ?></span><span><?= (int)$skill['proficiency'] ?>%</span></div>
              <div class="skill-bar"><div class="skill-bar-fill" data-width="<?= (int)$skill['proficiency'] ?>"></div></div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Education & Experience -->
<section class="section">
  <div class="container">
    <div class="grid grid-2">
      <div class="reveal">
        <span class="eyebrow"><?= icon('graduation-cap') ?> <?= e(t('about.education_eyebrow')) ?></span>
        <h2 class="section-title" style="margin-bottom:32px;"><?= e(t('about.education_title')) ?></h2>
        <div class="timeline">
          <?php foreach ($education as $ed): ?>
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-date"><?= e($ed['start_date']) ?> — <?= e($ed['end_date'] ?: t('common.present')) ?></div>
              <h3><?= e(tf($ed, 'degree')) ?></h3>
              <div class="org"><?= e(tf($ed, 'institution')) ?><?= $ed['field'] ? ' · ' . e(tf($ed, 'field')) : '' ?></div>
              <p><?= e(tf($ed, 'description')) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="reveal">
        <span class="eyebrow"><?= icon('briefcase') ?> <?= e(t('about.experience_eyebrow')) ?></span>
        <h2 class="section-title" style="margin-bottom:32px;"><?= e(t('about.experience_title')) ?></h2>
        <div class="timeline">
          <?php foreach ($experience as $ex): ?>
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-date"><?= e($ex['start_date']) ?> — <?= $ex['is_current'] ? e(t('common.present')) : e($ex['end_date']) ?></div>
              <h3><?= e(tf($ex, 'position')) ?></h3>
              <div class="org"><?= e(tf($ex, 'company')) ?><?= $ex['location'] ? ' · ' . e(tf($ex, 'location')) : '' ?></div>
              <p><?= e(tf($ex, 'description')) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Certificates -->
<section class="section section--alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= icon('award') ?> <?= e(t('about.certificates_eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('about.certificates_title')) ?></h2>
    </div>
    <div class="grid grid-3">
      <?php foreach ($certificates as $cert): ?>
        <a href="<?= e($cert['credential_url']) ?>" target="_blank" rel="noopener" class="card cert-card reveal">
          <div class="cert-icon"><?= icon('award') ?></div>
          <div>
            <h4><?= e(tf($cert, 'title')) ?></h4>
            <p><?= e(tf($cert, 'issuer')) ?> · <?= e($cert['issue_date']) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
