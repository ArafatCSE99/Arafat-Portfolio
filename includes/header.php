<?php
/**
 * Shared site header.
 * Expects (optional): $meta = seo_meta(...), $activePage = 'home'|'about'|'services'|'projects'|'blog'|'tools'|'contact'
 */
require_once __DIR__ . '/functions.php';

$meta = $meta ?? seo_meta('');
$activePage = $activePage ?? '';
$base = rtrim(SITE_URL, '/');

$navItems = [
    'home' => ['label' => t('nav.home'), 'href' => $base . '/'],
    'about' => ['label' => t('nav.about'), 'href' => $base . '/about.php'],
    'services' => ['label' => t('nav.services'), 'href' => $base . '/services.php'],
    'projects' => ['label' => t('nav.projects'), 'href' => $base . '/projects.php'],
    'blog' => ['label' => t('nav.blog'), 'href' => $base . '/blog.php'],
    'tools' => ['label' => t('nav.tools'), 'href' => $base . '/tools/index.php'],
    'faq' => ['label' => t('nav.faq'), 'href' => $base . '/faq.php'],
    'contact' => ['label' => t('nav.contact'), 'href' => $base . '/contact.php'],
];
?>
<!DOCTYPE html>
<html lang="<?= e(lang()) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($meta['title']) ?></title>
<meta name="description" content="<?= e($meta['description']) ?>">
<link rel="canonical" href="<?= e($meta['url']) ?>">

<meta property="og:type" content="<?= e($meta['type']) ?>">
<meta property="og:title" content="<?= e($meta['title']) ?>">
<meta property="og:description" content="<?= e($meta['description']) ?>">
<meta property="og:image" content="<?= e($meta['image']) ?>">
<meta property="og:url" content="<?= e($meta['url']) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($meta['title']) ?>">
<meta name="twitter:description" content="<?= e($meta['description']) ?>">

<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><text y=%2218%22 font-size=%2218%22>💼</text></svg>">
<link rel="stylesheet" href="<?= e(asset_url('assets/css/style.css')) ?>">
</head>
<body>

<header class="site-header" id="top">
  <div class="container">
    <a href="<?= e($base) ?>/" class="brand">
      <span class="brand-mark"><?= e(strtoupper(substr(setting('site_title', 'A'), 0, 1))) ?></span>
      <span><?= e(setting('site_title', 'Portfolio')) ?></span>
    </a>

    <nav class="nav-links" id="navLinks">
      <?php foreach ($navItems as $key => $item): ?>
        <a href="<?= e($item['href']) ?>" class="<?= $activePage === $key ? 'active' : '' ?>"><?= e($item['label']) ?></a>
      <?php endforeach; ?>
    </nav>

    <span class="nav-clock" id="navClock" aria-label="Dhaka time and temperature">
      <?= icon('clock') ?><span id="navClockTime">--:--</span>
      <span class="nav-clock-sep" aria-hidden="true">·</span>
      <?= icon('sun') ?><span id="navClockTemp">--°C</span>
    </span>

    <div class="header-actions">
      <button class="nav-toggle" type="button" aria-label="Toggle menu" id="navToggleBtn">
        <?= icon('menu') ?>
      </button>
    </div>
  </div>
</header>

<?php if ($activePage !== 'home'): ?>
<button type="button" class="back-fab" id="backFab" aria-label="Go back"><?= icon('chevron-left') ?></button>
<?php endif; ?>

<main>
