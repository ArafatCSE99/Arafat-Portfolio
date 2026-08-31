<?php
require_once __DIR__ . '/../auth.php';

$projects = db()->query('SELECT * FROM projects ORDER BY created_at DESC')->fetchAll();

$pageTitle = 'Projects';
$activeAdmin = 'projects';
require __DIR__ . '/../layout-top.php';
?>

<div class="admin-topbar">
  <h1>Projects</h1>
  <a href="<?= e($adminBase) ?>/projects/form.php" class="btn btn-primary"><?= icon('plus') ?> Add Project</a>
</div>

<div class="table-wrap">
  <table class="admin-table">
    <thead><tr><th>Title</th><th>Category</th><th>Featured</th><th>Created</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($projects as $p): ?>
        <tr>
          <td><strong><?= e($p['title']) ?></strong></td>
          <td><?= e($p['category']) ?></td>
          <td><?php if ($p['featured']): ?><span class="pill pill-success">Featured</span><?php else: ?><span class="pill pill-muted">No</span><?php endif; ?></td>
          <td><?= e(format_date($p['created_at'])) ?></td>
          <td>
            <div class="row-actions">
              <a class="icon-btn" href="<?= e($adminBase) ?>/projects/form.php?id=<?= (int)$p['id'] ?>" title="Edit"><?= icon('edit') ?></a>
              <form method="post" action="<?= e($adminBase) ?>/projects/delete.php" onsubmit="return confirm('Delete this project? This cannot be undone.');">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <button type="submit" class="icon-btn danger" title="Delete"><?= icon('trash') ?></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$projects): ?>
        <tr><td colspan="5" style="text-align:center;color:var(--text-muted);">No projects yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../layout-bottom.php'; ?>
