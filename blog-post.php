<?php
require_once __DIR__ . '/includes/functions.php';
$base = rtrim(SITE_URL, '/');

$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare('SELECT * FROM blog_posts WHERE slug = ? AND published = 1');
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    $meta = seo_meta('Article Not Found');
    $activePage = 'blog';
    require __DIR__ . '/includes/header.php';
    echo '<div class="section container empty-state">' . icon('edit') . '<p>' . e(t('blog.not_found')) . '</p><a href="' . e($base) . '/blog.php" class="btn btn-primary" style="margin-top:16px;">' . e(t('blog.back')) . '</a></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

db()->prepare('UPDATE blog_posts SET views = views + 1 WHERE id = ?')->execute([$post['id']]);

$related = db()->prepare('SELECT * FROM blog_posts WHERE category = ? AND id != ? AND published = 1 LIMIT 3');
$related->execute([$post['category'], $post['id']]);
$relatedPosts = $related->fetchAll();

$meta = seo_meta(tf($post, 'title'), tf($post, 'excerpt'), $post['cover_image'], 'article');
$activePage = 'blog';

require __DIR__ . '/includes/header.php';
?>

<?php $hasBn = !empty($post['title_bn']) && !empty($post['content_bn']); ?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <a href="<?= e($base) ?>/blog.php"><?= e(t('nav.blog')) ?></a> / <span><?= e(tf($post, 'title')) ?></span></div>
    <span class="badge" style="margin:0 auto 14px;"><?= e($post['category']) ?></span>
    <h1 class="section-title" style="max-width:800px;margin:0 auto 16px;" id="postTitle" data-en="<?= e($post['title']) ?>" data-bn="<?= e($post['title_bn'] ?: $post['title']) ?>"><?= e(tf($post, 'title')) ?></h1>
    <div class="meta-row" style="justify-content:center;">
      <span><?= icon('calendar') ?> <?= e(format_date($post['created_at'])) ?></span>
      <span><?= icon('eye') ?> <?= (int)$post['views'] ?> <?= e(t('blog.views')) ?></span>
      <?php if ($hasBn): ?>
        <button type="button" class="lang-switch-btn" id="langSwitchBtn" data-label-en="<?= e(t('blog.read_in_english')) ?>" data-label-bn="<?= e(t('blog.read_in_bangla')) ?>">
          <?= icon('sparkle') ?><span id="langSwitchLabel"><?= e(t('blog.read_in_bangla')) ?></span>
        </button>
      <?php endif; ?>
    </div>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:800px;">
    <img src="<?= e($base) ?>/<?= e($post['cover_image']) ?>" alt="<?= e(tf($post, 'title')) ?>" style="width:100%;border-radius:22px;box-shadow:var(--shadow-lg);margin-bottom:40px;">

    <article style="font-size:1.05rem;color:var(--text);line-height:1.85;" class="blog-content" id="articleEn">
      <?= $post['content'] ?>
    </article>
    <?php if ($hasBn): ?>
    <article style="font-size:1.05rem;color:var(--text);line-height:1.85;" class="blog-content" id="articleBn" lang="bn" hidden>
      <?= $post['content_bn'] ?>
    </article>
    <?php endif; ?>

    <?php if ($post['tags']): ?>
    <div class="tech-tags" style="margin-top:36px;">
      <?php foreach (array_map('trim', explode(',', $post['tags'])) as $tag): ?>
        <span class="tech-tag"><?= icon('tag', 'icon') ?> <?= e($tag) ?></span>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <a href="<?= e($base) ?>/blog.php" class="back-link" style="margin-top:40px;"><?= icon('arrow-left') ?> <?= e(t('nav.blog')) ?></a>

    <?php if ($relatedPosts): ?>
    <div style="margin-top:64px;">
      <h2 style="font-size:1.4rem;margin-bottom:24px;"><?= e(t('blog.related')) ?></h2>
      <div class="grid grid-3">
        <?php foreach ($relatedPosts as $rp): ?>
          <div class="card blog-card reveal">
            <div class="blog-thumb"><a href="<?= e($base) ?>/blog/<?= e($rp['slug']) ?>"><img src="<?= e($base) ?>/<?= e($rp['cover_image']) ?>" alt="<?= e(tf($rp, 'title')) ?>"></a></div>
            <div class="blog-body">
              <h3 class="blog-title"><a href="<?= e($base) ?>/blog/<?= e($rp['slug']) ?>"><?= e(tf($rp, 'title')) ?></a></h3>
              <a href="<?= e($base) ?>/blog/<?= e($rp['slug']) ?>" class="read-more"><?= e(t('common.read_article')) ?> <?= icon('arrow-right') ?></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
