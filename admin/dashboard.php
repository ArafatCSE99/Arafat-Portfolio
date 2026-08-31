<?php
require_once __DIR__ . '/auth.php';

$stats = [
    'Projects' => db()->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
    'Blog Posts' => db()->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn(),
    'Messages' => db()->query('SELECT COUNT(*) FROM messages')->fetchColumn(),
    'Unread Messages' => db()->query('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn(),
];

$recentMessages = db()->query('SELECT * FROM messages ORDER BY created_at DESC LIMIT 5')->fetchAll();
$recentPosts = db()->query('SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 5')->fetchAll();

$viewsRows = db()->query('SELECT title, views FROM blog_posts ORDER BY views DESC LIMIT 8')->fetchAll();
$viewsLabels = array_map(fn($r) => truncate($r['title'], 22), $viewsRows);
$viewsData = array_map(fn($r) => (int)$r['views'], $viewsRows);

$catRows = db()->query('SELECT category, COUNT(*) AS cnt FROM projects GROUP BY category ORDER BY cnt DESC')->fetchAll();
$catLabels = array_map(fn($r) => $r['category'], $catRows);
$catData = array_map(fn($r) => (int)$r['cnt'], $catRows);

$pageTitle = 'Dashboard';
$activeAdmin = 'dashboard';
$pageScripts = ['assets/js/vendor/chart.min.js', 'assets/js/admin-dashboard.js'];
require __DIR__ . '/layout-top.php';
?>

<div class="admin-topbar">
  <h1>Welcome back, <?= e($_SESSION['admin_username']) ?> 👋</h1>
</div>

<div class="grid grid-4" style="margin-bottom:32px;">
  <?php foreach ($stats as $label => $num): ?>
    <div class="card stat-card">
      <div class="num"><?= (int)$num ?></div>
      <div class="label"><?= e($label) ?></div>
    </div>
  <?php endforeach; ?>
</div>

<div class="grid grid-2" style="margin-bottom:32px;">
  <div class="card chart-card">
    <h3>Blog Post Views</h3>
    <p class="chart-sub">Views per published article</p>
    <div class="chart-wrap"><canvas id="viewsChart"></canvas></div>
  </div>
  <div class="card chart-card">
    <h3>Projects by Category</h3>
    <p class="chart-sub">How your portfolio is distributed</p>
    <div class="chart-wrap"><canvas id="categoryChart"></canvas></div>
  </div>
</div>

<script>
  window.__dashboardData = {
    views: { labels: <?= json_encode($viewsLabels) ?>, data: <?= json_encode($viewsData) ?> },
    categories: { labels: <?= json_encode($catLabels) ?>, data: <?= json_encode($catData) ?> }
  };
</script>

<div class="grid grid-2">
  <div class="card" style="padding:24px;">
    <h3 style="margin-bottom:16px;">Recent Messages</h3>
    <?php if (!$recentMessages): ?>
      <p style="color:var(--text-muted);font-size:.9rem;">No messages yet.</p>
    <?php else: ?>
      <?php foreach ($recentMessages as $m): ?>
        <div style="padding:12px 0;border-bottom:1px solid var(--border);">
          <strong style="font-size:.92rem;"><?= e($m['name']) ?></strong>
          <span class="pill <?= $m['is_read'] ? 'pill-muted' : 'pill-success' ?>" style="margin-left:8px;"><?= $m['is_read'] ? 'Read' : 'New' ?></span>
          <p style="color:var(--text-muted);font-size:.85rem;margin-top:4px;"><?= e(truncate($m['message'], 80)) ?></p>
        </div>
      <?php endforeach; ?>
      <a href="<?= e($adminBase) ?>/messages.php" class="btn btn-outline btn-sm" style="margin-top:14px;">View All Messages</a>
    <?php endif; ?>
  </div>

  <div class="card" style="padding:24px;">
    <h3 style="margin-bottom:16px;">Recent Blog Posts</h3>
    <?php if (!$recentPosts): ?>
      <p style="color:var(--text-muted);font-size:.9rem;">No blog posts yet.</p>
    <?php else: ?>
      <?php foreach ($recentPosts as $p): ?>
        <div style="padding:12px 0;border-bottom:1px solid var(--border);">
          <strong style="font-size:.92rem;"><?= e($p['title']) ?></strong>
          <span class="pill <?= $p['published'] ? 'pill-success' : 'pill-muted' ?>" style="margin-left:8px;"><?= $p['published'] ? 'Published' : 'Draft' ?></span>
        </div>
      <?php endforeach; ?>
      <a href="<?= e($adminBase) ?>/blog/list.php" class="btn btn-outline btn-sm" style="margin-top:14px;">Manage Blog Posts</a>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/layout-bottom.php'; ?>
