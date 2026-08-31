<?php
require_once __DIR__ . '/includes/functions.php';

$meta = seo_meta('Home', setting_t('bio_short'));
$activePage = 'home';

$featuredProjects = db()->query('SELECT * FROM projects ORDER BY featured DESC, created_at DESC LIMIT 3')->fetchAll();
$latestPosts = db()->query('SELECT * FROM blog_posts WHERE published = 1 ORDER BY created_at DESC LIMIT 3')->fetchAll();
$skills = db()->query('SELECT * FROM skills ORDER BY sort_order LIMIT 8')->fetchAll();
$services = db()->query('SELECT * FROM services ORDER BY sort_order LIMIT 4')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="hero">
  <div class="container hero-grid">
    <div class="hero-enter d1">
      <span class="eyebrow"><?= icon('sparkle') ?> <?= e(t('home.hero_eyebrow')) ?></span>
      <h1 class="hero-title"><?= e(t('home.hero_greeting')) ?> <span class="gradient-text"><?= e(setting('site_title')) ?></span><br><?= e(setting_t('site_tagline')) ?></h1>
      <p class="hero-sub"><?= e(setting_t('bio_short')) ?></p>
      <div class="btn-row">
        <a href="<?= e(rtrim(SITE_URL,'/')) ?>/projects.php" class="btn btn-primary"><?= icon('briefcase') ?> <?= e(t('home.view_work')) ?></a>
        <a href="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e(setting('cv_path')) ?>" class="btn btn-outline" download><?= icon('download') ?> <?= e(t('home.download_cv')) ?></a>
      </div>
      <div class="social-row">
        <?php foreach (['github', 'linkedin', 'facebook', 'twitter', 'instagram'] as $s): ?>
          <?php $url = setting('social_' . $s); if (!$url) continue; ?>
          <a class="social-btn" href="<?= e($url) ?>" target="_blank" rel="noopener" aria-label="<?= e($s) ?>"><?= icon($s) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="hero-photo-wrap hero-enter d2">
      <img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e(setting('avatar')) ?>" alt="<?= e(setting('site_title')) ?>" class="hero-photo">
      <div class="hero-badge"><?= icon('award') ?> <?= e(t('home.experience_badge')) ?></div>
    </div>
  </div>
</section>

<!-- About summary -->
<section class="section section--alt">
  <div class="container">
    <div class="hero-grid" style="grid-template-columns:.9fr 1.1fr;align-items:center;">
      <div class="reveal about-photo-wrap">
        <img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e(setting('avatar')) ?>" alt="About <?= e(setting('site_title')) ?>" class="about-hero-photo">
      </div>
      <div class="reveal">
        <span class="eyebrow"><?= e(t('home.about_eyebrow')) ?></span>
        <h2 class="section-title"><?= e(t('home.about_title')) ?></h2>
        <p class="section-desc" style="text-align:left;margin-bottom:24px;"><?= e(setting_t('bio_long')) ?></p>
        <a href="<?= e(rtrim(SITE_URL,'/')) ?>/about.php" class="btn btn-primary"><?= icon('arrow-right') ?> <?= e(t('home.about_btn')) ?></a>
      </div>
    </div>
  </div>
</section>

<!-- Skills preview -->
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(t('home.skills_eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('home.skills_title')) ?></h2>
      <p class="section-desc"><?= e(t('home.skills_desc')) ?></p>
    </div>
    <div class="grid grid-4">
      <?php foreach ($skills as $skill): ?>
        <div class="card reveal" style="padding:26px;text-align:center;">
          <div class="service-icon" style="margin:0 auto 14px;"><?= icon($skill['icon_key']) ?></div>
          <h3 style="font-size:1rem;margin-bottom:6px;"><?= e($skill['name']) ?></h3>
          <span style="color:var(--text-muted);font-size:.82rem;"><?= e($skill['category']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured projects -->
<section class="section section--alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(t('home.projects_eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('home.projects_title')) ?></h2>
      <p class="section-desc"><?= e(t('home.projects_desc')) ?></p>
    </div>
    <div class="grid grid-3">
      <?php foreach ($featuredProjects as $p): ?>
        <div class="card project-card reveal">
          <div class="project-thumb"><img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e($p['cover_image']) ?>" alt="<?= e(tf($p, 'title')) ?>"></div>
          <div class="project-body">
            <span class="badge"><?= e($p['category']) ?></span>
            <h3 class="project-title"><a href="<?= e(rtrim(SITE_URL,'/')) ?>/project/<?= e($p['slug']) ?>"><?= e(tf($p, 'title')) ?></a></h3>
            <p class="project-desc"><?= e(tf($p, 'summary')) ?></p>
            <a href="<?= e(rtrim(SITE_URL,'/')) ?>/project/<?= e($p['slug']) ?>" class="read-more"><?= e(t('common.view_details')) ?> <?= icon('arrow-right') ?></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:44px;">
      <a href="<?= e(rtrim(SITE_URL,'/')) ?>/projects.php" class="btn btn-outline"><?= e(t('home.see_all_projects')) ?> <?= icon('arrow-right') ?></a>
    </div>
  </div>
</section>

<!-- Services preview -->
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(t('home.services_eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('home.services_title')) ?></h2>
      <p class="section-desc"><?= e(t('services.desc')) ?></p>
    </div>
    <div class="grid grid-4">
      <?php foreach ($services as $s): ?>
        <div class="card service-card reveal">
          <?php if (!empty($s['image'])): ?>
            <div class="service-thumb"><img src="<?= e($base) ?>/<?= e($s['image']) ?>" alt="<?= e(tf($s, 'title')) ?>"></div>
          <?php else: ?>
            <div class="service-icon" style="margin:24px 24px 0;"><?= icon($s['icon_key']) ?></div>
          <?php endif; ?>
          <div class="service-card-body">
            <h3><?= e(tf($s, 'title')) ?></h3>
            <p><?= e(truncate(tf($s, 'description'), 90)) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Latest blog posts -->
<section class="section section--alt">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow"><?= e(t('home.blog_eyebrow')) ?></span>
      <h2 class="section-title"><?= e(t('home.blog_title')) ?></h2>
      <p class="section-desc"><?= e(t('home.blog_desc')) ?></p>
    </div>
    <div class="grid grid-3">
      <?php foreach ($latestPosts as $post): ?>
        <div class="card blog-card reveal">
          <div class="blog-thumb"><a href="<?= e(rtrim(SITE_URL,'/')) ?>/blog/<?= e($post['slug']) ?>"><img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e($post['cover_image']) ?>" alt="<?= e(tf($post, 'title')) ?>"></a></div>
          <div class="blog-body">
            <div class="meta-row">
              <span><?= icon('calendar') ?> <?= e(format_date($post['created_at'])) ?></span>
              <span><?= icon('tag') ?> <?= e($post['category']) ?></span>
            </div>
            <h3 class="blog-title"><a href="<?= e(rtrim(SITE_URL,'/')) ?>/blog/<?= e($post['slug']) ?>"><?= e(tf($post, 'title')) ?></a></h3>
            <p class="project-desc"><?= e(truncate(tf($post, 'excerpt'), 100)) ?></p>
            <a href="<?= e(rtrim(SITE_URL,'/')) ?>/blog/<?= e($post['slug']) ?>" class="read-more"><?= e(t('common.read_article')) ?> <?= icon('arrow-right') ?></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Contact CTA -->
<section class="section">
  <div class="container">
    <div class="card reveal" style="padding:56px;text-align:center;background:var(--gradient-brand);color:#fff;border:none;">
      <h2 style="font-size:1.9rem;margin-bottom:14px;"><?= e(t('home.cta_title')) ?></h2>
      <p style="opacity:.9;max-width:520px;margin:0 auto 28px;"><?= e(t('home.cta_desc')) ?></p>
      <a href="<?= e(rtrim(SITE_URL,'/')) ?>/contact.php" class="btn" style="background:#fff;color:var(--primary);"><?= icon('send') ?> <?= e(t('home.cta_btn')) ?></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
