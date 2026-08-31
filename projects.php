<?php
require_once __DIR__ . '/includes/functions.php';

$meta = seo_meta('Projects', 'A showcase of web applications and websites I have built.');
$activePage = 'projects';
$base = rtrim(SITE_URL, '/');

$projects = db()->query('SELECT * FROM projects ORDER BY featured DESC, created_at DESC')->fetchAll();
$categories = array_values(array_unique(array_column($projects, 'category')));

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <span><?= e(t('nav.projects')) ?></span></div>
    <span class="eyebrow"><?= e(t('home.projects_eyebrow')) ?></span>
    <h1 class="section-title"><?= e(t('projects.heading')) ?></h1>
    <p class="section-desc"><?= e(t('projects.desc')) ?></p>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">
    <?php if ($categories): ?>
    <div class="filter-bar">
      <button class="filter-chip active" data-filter="all"><?= e(t('projects.filter_all')) ?></button>
      <?php foreach ($categories as $cat): ?>
        <button class="filter-chip" data-filter="<?= e($cat) ?>"><?= e($cat) ?></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!$projects): ?>
      <div class="empty-state"><?= icon('briefcase') ?><p><?= e(t('projects.empty')) ?></p></div>
    <?php else: ?>
    <div class="grid grid-3">
      <?php foreach ($projects as $p): ?>
        <div class="card project-card reveal" data-category="<?= e($p['category']) ?>">
          <div class="project-thumb"><img src="<?= e($base) ?>/<?= e($p['cover_image']) ?>" alt="<?= e(tf($p, 'title')) ?>"></div>
          <div class="project-body">
            <span class="badge"><?= e($p['category']) ?></span>
            <h3 class="project-title"><a href="<?= e($base) ?>/project/<?= e($p['slug']) ?>"><?= e(tf($p, 'title')) ?></a></h3>
            <p class="project-desc"><?= e(tf($p, 'summary')) ?></p>
            <div class="tech-tags">
              <?php foreach (array_slice(array_map('trim', explode(',', $p['technologies'])), 0, 4) as $t): ?>
                <span class="tech-tag"><?= e($t) ?></span>
              <?php endforeach; ?>
            </div>
            <a href="<?= e($base) ?>/project/<?= e($p['slug']) ?>" class="read-more"><?= e(t('common.view_details')) ?> <?= icon('arrow-right') ?></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
