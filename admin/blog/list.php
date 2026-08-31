<?php
require_once __DIR__ . '/../auth.php';

$posts = db()->query('SELECT * FROM blog_posts ORDER BY created_at DESC')->fetchAll();

$pageTitle = 'Blog Posts';
$activeAdmin = 'blog';
require __DIR__ . '/../layout-top.php';
?>

<div class="admin-topbar">
  <h1>Blog Posts</h1>
  <a href="<?= e($adminBase) ?>/blog/form.php" class="btn btn-primary"><?= icon('plus') ?> Add Post</a>
</div>

<div class="table-wrap">
  <table class="admin-table">
    <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Views</th><th>Created</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($posts as $p): ?>
        <tr>
          <td><strong><?= e($p['title']) ?></strong></td>
          <td><?= e($p['category']) ?></td>
          <td><?php if ($p['published']): ?><span class="pill pill-success">Published</span><?php else: ?><span class="pill pill-muted">Draft</span><?php endif; ?></td>
          <td><?= (int)$p['views'] ?></td>
          <td><?= e(format_date($p['created_at'])) ?></td>
          <td>
            <div class="row-actions">
              <a class="icon-btn" href="<?= e($adminBase) ?>/blog/form.php?id=<?= (int)$p['id'] ?>" title="Edit"><?= icon('edit') ?></a>
              <form method="post" action="<?= e($adminBase) ?>/blog/delete.php" onsubmit="return confirm('Delete this post? This cannot be undone.');">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <button type="submit" class="icon-btn danger" title="Delete"><?= icon('trash') ?></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$posts): ?>
        <tr><td colspan="6" style="text-align:center;color:var(--text-muted);">No blog posts yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../layout-bottom.php'; ?>
