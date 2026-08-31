<?php
require_once __DIR__ . '/auth.php';

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editItem = ['title' => '', 'description' => '', 'icon_key' => 'code', 'image' => '', 'sort_order' => 0];
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([$editId]);
    $found = $stmt->fetch();
    if ($found) $editItem = $found;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        db()->prepare('DELETE FROM services WHERE id = ?')->execute([(int)$_POST['id']]);
        flash_set('success', 'Service deleted.');
        redirect($adminBase . '/services.php');
    }

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon_key = trim($_POST['icon_key'] ?? 'code');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $image = trim($_POST['existing_image'] ?? '');

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    if (!$errors) {
        try {
            $newImage = handle_upload('image', 'services');
            if ($newImage) {
                $image = $newImage;
            }
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }

    if (!$errors) {
        if ($action === 'update') {
            $stmt = db()->prepare('UPDATE services SET title=?, description=?, icon_key=?, image=?, sort_order=? WHERE id=?');
            $stmt->execute([$title, $description, $icon_key, $image, $sort_order, (int)$_POST['id']]);
            flash_set('success', 'Service updated.');
        } else {
            $stmt = db()->prepare('INSERT INTO services (title, description, icon_key, image, sort_order) VALUES (?,?,?,?,?)');
            $stmt->execute([$title, $description, $icon_key, $image, $sort_order]);
            flash_set('success', 'Service added.');
        }
        redirect($adminBase . '/services.php');
    }
}

$items = db()->query('SELECT * FROM services ORDER BY sort_order, id')->fetchAll();
$iconOptions = ['code', 'server', 'database', 'git', 'palette', 'settings', 'briefcase'];

$pageTitle = 'Services';
$activeAdmin = 'services';
require __DIR__ . '/layout-top.php';
?>

<div class="admin-topbar"><h1>Services</h1></div>

<?php foreach ($errors as $err): ?><div class="alert alert-error"><?= icon('close') ?> <?= e($err) ?></div><?php endforeach; ?>

<div class="card" style="padding:24px;margin-bottom:28px;">
  <h3 style="margin-bottom:16px;"><?= $editId ? 'Edit Service' : 'Add New Service' ?></h3>
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="<?= $editId ? 'update' : 'create' ?>">
    <input type="hidden" name="existing_image" value="<?= e($editItem['image'] ?? '') ?>">
    <?php if ($editId): ?><input type="hidden" name="id" value="<?= (int)$editId ?>"><?php endif; ?>
    <div class="form-group">
      <label>Photo</label>
      <?php if (!empty($editItem['image'])): ?>
        <img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e($editItem['image']) ?>" style="width:220px;aspect-ratio:16/10;object-fit:cover;border-radius:10px;margin-bottom:10px;display:block;">
      <?php endif; ?>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>
    <div class="grid grid-4">
      <div class="form-group" style="grid-column:span 2;"><label>Title</label><input type="text" name="title" class="form-control" value="<?= e($editItem['title']) ?>" required></div>
      <div class="form-group"><label>Icon (fallback)</label>
        <select name="icon_key" class="form-control">
          <?php foreach ($iconOptions as $ic): ?><option value="<?= e($ic) ?>" <?= $editItem['icon_key'] === $ic ? 'selected' : '' ?>><?= e($ic) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="<?= (int)$editItem['sort_order'] ?>"></div>
    </div>
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control"><?= e($editItem['description']) ?></textarea></div>
    <button type="submit" class="btn btn-primary"><?= icon('check') ?> <?= $editId ? 'Update' : 'Add' ?> Service</button>
    <?php if ($editId): ?><a href="<?= e($adminBase) ?>/services.php" class="btn btn-ghost">Cancel</a><?php endif; ?>
  </form>
</div>

<div class="table-wrap">
  <table class="admin-table">
    <thead><tr><th>Photo</th><th>Title</th><th>Sort</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td>
            <?php if (!empty($it['image'])): ?>
              <img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e($it['image']) ?>" style="width:56px;height:38px;object-fit:cover;border-radius:6px;display:block;">
            <?php else: ?>
              <div class="icon-btn"><?= icon($it['icon_key']) ?></div>
            <?php endif; ?>
          </td>
          <td><strong><?= e($it['title']) ?></strong></td>
          <td><?= (int)$it['sort_order'] ?></td>
          <td>
            <div class="row-actions">
              <a class="icon-btn" href="?edit=<?= (int)$it['id'] ?>"><?= icon('edit') ?></a>
              <form method="post" onsubmit="return confirm('Delete this service?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                <button type="submit" class="icon-btn danger"><?= icon('trash') ?></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?><tr><td colspan="4" style="text-align:center;color:var(--text-muted);">No services yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/layout-bottom.php'; ?>
