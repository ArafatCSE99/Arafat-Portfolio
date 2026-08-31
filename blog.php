<?php
require_once __DIR__ . '/includes/functions.php';

$meta = seo_meta('Blog', 'Articles and tutorials on web development, design, and more.');
$activePage = 'blog';
$base = rtrim(SITE_URL, '/');

$posts = db()->query('SELECT * FROM blog_posts WHERE published = 1 ORDER BY created_at DESC')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <span><?= e(t('nav.blog')) ?></span></div>
    <span class="eyebrow"><?= e(t('home.blog_eyebrow')) ?></span>
    <h1 class="section-title"><?= e(t('blog.heading')) ?></h1>
    <p class="section-desc"><?= e(t('blog.desc')) ?></p>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">
    <div style="max-width:420px;margin:0 auto 44px;position:relative;">
      <input type="search" id="blog-search" class="form-control" placeholder="<?= e(t('blog.search_placeholder')) ?>" style="padding-left:44px;">
      <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-soft);"><?= icon('search') ?></span>
    </div>

    <?php if (!$posts): ?>
      <div class="empty-state"><?= icon('edit') ?><p><?= e(t('blog.empty')) ?></p></div>
    <?php else: ?>
    <div class="grid grid-3">
      <?php foreach ($posts as $post): ?>
        <div class="card blog-card reveal" data-search="<?= e($post['title'] . ' ' . $post['title_bn'] . ' ' . $post['category'] . ' ' . $post['tags']) ?>">
          <div class="blog-thumb"><a href="<?= e($base) ?>/blog/<?= e($post['slug']) ?>"><img src="<?= e($base) ?>/<?= e($post['cover_image']) ?>" alt="<?= e(tf($post, 'title')) ?>"></a></div>
          <div class="blog-body">
            <div class="meta-row">
              <span><?= icon('calendar') ?> <?= e(format_date($post['created_at'])) ?></span>
              <span><?= icon('tag') ?> <?= e($post['category']) ?></span>
              <span><?= icon('eye') ?> <?= (int)$post['views'] ?></span>
            </div>
            <h3 class="blog-title"><a href="<?= e($base) ?>/blog/<?= e($post['slug']) ?>"><?= e(tf($post, 'title')) ?></a></h3>
            <p class="project-desc"><?= e(truncate(tf($post, 'excerpt'), 110)) ?></p>
            <a href="<?= e($base) ?>/blog/<?= e($post['slug']) ?>" class="read-more"><?= e(t('common.read_article')) ?> <?= icon('arrow-right') ?></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
