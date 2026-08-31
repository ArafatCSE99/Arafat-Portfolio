<?php
require_once __DIR__ . '/includes/functions.php';
$base = rtrim(SITE_URL, '/');

$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare('SELECT * FROM projects WHERE slug = ?');
$stmt->execute([$slug]);
$project = $stmt->fetch();

if (!$project) {
    http_response_code(404);
    $meta = seo_meta('Project Not Found');
    $activePage = 'projects';
    require __DIR__ . '/includes/header.php';
    echo '<div class="section container empty-state">' . icon('briefcase') . '<p>' . e(t('projects.not_found')) . '</p><a href="' . e($base) . '/projects.php" class="btn btn-primary" style="margin-top:16px;">' . e(t('projects.back')) . '</a></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$related = db()->prepare('SELECT * FROM projects WHERE category = ? AND id != ? LIMIT 3');
$related->execute([$project['category'], $project['id']]);
$relatedProjects = $related->fetchAll();

$meta = seo_meta(tf($project, 'title'), tf($project, 'summary'), $project['cover_image'], 'article');
$activePage = 'projects';
$hasRealDemo = $project['demo_url'] && !str_contains($project['demo_url'], 'example.com');

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <a href="<?= e($base) ?>/projects.php"><?= e(t('nav.projects')) ?></a> / <span><?= e(tf($project, 'title')) ?></span></div>
    <span class="badge" style="margin:0 auto 14px;"><?= e($project['category']) ?></span>
    <h1 class="section-title"><?= e(tf($project, 'title')) ?></h1>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">
    <img src="<?= e($base) ?>/<?= e($project['cover_image']) ?>" alt="<?= e(tf($project, 'title')) ?>" style="width:100%;border-radius:22px;box-shadow:var(--shadow-lg);margin-bottom:44px;">

    <div class="hero-grid" style="grid-template-columns:1fr .5fr;align-items:start;">
      <div class="reveal">
        <h2 style="font-size:1.4rem;margin-bottom:16px;"><?= e(t('projects.overview')) ?></h2>
        <p style="color:var(--text-muted);white-space:pre-line;"><?= e(tf($project, 'description')) ?></p>
      </div>
      <div class="card reveal" style="padding:28px;">
        <h3 style="font-size:1rem;margin-bottom:18px;"><?= e(t('projects.info')) ?></h3>
        <div style="margin-bottom:16px;">
          <strong style="display:block;font-size:.82rem;color:var(--text-soft);margin-bottom:8px;text-transform:uppercase;"><?= e(t('projects.technologies')) ?></strong>
          <div class="tech-tags">
            <?php foreach (array_map('trim', explode(',', $project['technologies'])) as $t): ?>
              <span class="tech-tag"><?= e($t) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="project-links" style="flex-direction:column;">
          <?php if ($hasRealDemo): ?>
            <a href="<?= e($project['demo_url']) ?>" target="_blank" rel="noopener" class="btn btn-primary btn-block"><?= icon('external-link') ?> <?= e(t('projects.live_demo')) ?></a>
          <?php else: ?>
            <button type="button" class="btn btn-primary btn-block" data-demo-open data-demo-img="<?= e($base . '/' . $project['cover_image']) ?>" data-demo-title="<?= e(tf($project, 'title')) ?>" data-demo-slug="<?= e($project['slug']) ?>"><?= icon('external-link') ?> <?= e(t('projects.live_demo')) ?></button>
          <?php endif; ?>
          <?php if ($project['github_url'] && $project['github_url'] !== 'https://github.com/'): ?>
            <a href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener" class="btn btn-outline btn-block"><?= icon('github') ?> <?= e(t('projects.source_code')) ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <a href="<?= e($base) ?>/projects.php" class="back-link" style="margin-top:40px;"><?= icon('arrow-left') ?> <?= e(t('nav.projects')) ?></a>

    <?php if ($relatedProjects): ?>
    <div style="margin-top:64px;">
      <h2 style="font-size:1.4rem;margin-bottom:24px;"><?= e(t('projects.related')) ?></h2>
      <div class="grid grid-3">
        <?php foreach ($relatedProjects as $p): ?>
          <div class="card project-card reveal">
            <div class="project-thumb"><img src="<?= e($base) ?>/<?= e($p['cover_image']) ?>" alt="<?= e(tf($p, 'title')) ?>"></div>
            <div class="project-body">
              <span class="badge"><?= e($p['category']) ?></span>
              <h3 class="project-title"><a href="<?= e($base) ?>/project/<?= e($p['slug']) ?>"><?= e(tf($p, 'title')) ?></a></h3>
              <a href="<?= e($base) ?>/project/<?= e($p['slug']) ?>" class="read-more"><?= e(t('common.view_details')) ?> <?= icon('arrow-right') ?></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php if (!$hasRealDemo): ?>
<div class="demo-modal-overlay" id="demoModalOverlay">
  <div class="demo-modal">
    <div class="demo-modal-bar">
      <div class="demo-modal-dots"><span class="demo-modal-dot"></span><span class="demo-modal-dot"></span><span class="demo-modal-dot"></span></div>
      <div class="demo-modal-url"><?= icon('eye') ?> <span id="demoModalUrl"></span></div>
      <button type="button" class="demo-modal-close" id="demoModalClose" aria-label="Close preview"><?= icon('close') ?></button>
    </div>
    <div class="demo-modal-body"><img id="demoModalImg" src="" alt=""></div>
    <div class="demo-modal-note"><?= icon('sparkle') ?> Live preview of this project's interface. Full source walkthrough available on request.</div>
  </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
