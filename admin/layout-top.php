<?php
/**
 * Shared admin layout header. Expects: $pageTitle, $activeAdmin (nav key)
 * Must be included AFTER admin/auth.php.
 */
$meta = seo_meta($pageTitle ?? 'Admin');
$activeAdmin = $activeAdmin ?? '';

$adminNav = [
    'dashboard' => ['label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => 'code'],
    'projects' => ['label' => 'Projects', 'href' => 'projects/list.php', 'icon' => 'briefcase'],
    'blog' => ['label' => 'Blog Posts', 'href' => 'blog/list.php', 'icon' => 'edit'],
    'skills' => ['label' => 'Skills', 'href' => 'skills.php', 'icon' => 'code'],
    'education' => ['label' => 'Education', 'href' => 'education.php', 'icon' => 'graduation-cap'],
    'experience' => ['label' => 'Experience', 'href' => 'experience.php', 'icon' => 'briefcase'],
    'services' => ['label' => 'Services', 'href' => 'services.php', 'icon' => 'settings'],
    'certificates' => ['label' => 'Certificates', 'href' => 'certificates.php', 'icon' => 'award'],
    'messages' => ['label' => 'Messages', 'href' => 'messages.php', 'icon' => 'mail'],
    'settings' => ['label' => 'Settings', 'href' => 'settings.php', 'icon' => 'settings'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($meta['title']) ?></title>
<link rel="stylesheet" href="<?= e(asset_url('assets/css/style.css')) ?>">
<link rel="stylesheet" href="<?= e(asset_url('assets/css/admin-theme.css')) ?>">
</head>
<body>
<div class="admin-shell">
  <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>
  <aside class="admin-sidebar" id="adminSidebar">
    <a href="<?= e($adminBase) ?>/dashboard.php" class="brand admin-brand">
      <span class="brand-mark"><?= e(strtoupper(substr(setting('site_title', 'A'), 0, 1))) ?></span>
      <span class="admin-brand-text">
        <strong><?= e(setting('site_title', 'Portfolio')) ?></strong>
        <small>Admin Panel</small>
      </span>
    </a>
    <div class="admin-clock" aria-label="Dhaka time and temperature">
      <?= icon('clock') ?><span id="navClockTime">--:--</span>
      <span class="admin-clock-sep" aria-hidden="true">·</span>
      <?= icon('sun') ?><span id="navClockTemp">--°C</span>
    </div>
    <nav class="admin-nav">
      <?php foreach ($adminNav as $key => $item): ?>
        <a href="<?= e($adminBase) ?>/<?= e($item['href']) ?>" class="<?= $activeAdmin === $key ? 'active' : '' ?>">
          <?= icon($item['icon']) ?> <span><?= e($item['label']) ?></span>
        </a>
      <?php endforeach; ?>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-usermenu">
      <button type="button" class="admin-sidebar-toggle" id="adminSidebarToggle" aria-label="Toggle menu"><?= icon('menu') ?></button>
      <span class="admin-usermenu-name"><?= icon('user') ?> <span class="admin-usermenu-name-label"><?= e($_SESSION['admin_username'] ?? 'Admin') ?></span></span>
      <a href="<?= e(rtrim(SITE_URL,'/')) ?>/" target="_blank" class="admin-usermenu-btn"><?= icon('external-link') ?> <span class="admin-usermenu-btn-label">View Site</span></a>
      <a href="<?= e($adminBase) ?>/logout.php" class="admin-usermenu-btn admin-usermenu-logout"><?= icon('logout') ?> <span class="admin-usermenu-btn-label">Logout</span></a>
    </div>
    <?php $flash = flash_get(); ?>
    <?php if ($flash): ?>
      <div class="alert alert-<?= e($flash['type']) ?>" data-autohide><?= icon($flash['type'] === 'success' ? 'check' : 'close') ?> <?= e($flash['message']) ?></div>
    <?php endif; ?>
