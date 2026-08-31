<?php
require_once __DIR__ . '/auth.php';

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editItem = ['position' => '', 'company' => '', 'location' => '', 'start_date' => '', 'end_date' => '', 'is_current' => 0, 'description' => '', 'sort_order' => 0];
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM experience WHERE id = ?');
    $stmt->execute([$editId]);
    $found = $stmt->fetch();
    if ($found) $editItem = $found;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        db()->prepare('DELETE FROM experience WHERE id = ?')->execute([(int)$_POST['id']]);
        flash_set('success', 'Entry deleted.');
        redirect($adminBase . '/experience.php');
    }

    $position = trim($_POST['position'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date = trim($_POST['end_date'] ?? '');
    $is_current = isset($_POST['is_current']) ? 1 : 0;
    $description = trim($_POST['description'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if ($position === '') {
        $errors[] = 'Position title is required.';
    }

    if (!$errors) {
        if ($action === 'update') {
            $stmt = db()->prepare('UPDATE experience SET position=?, company=?, location=?, start_date=?, end_date=?, is_current=?, description=?, sort_order=? WHERE id=?');
            $stmt->execute([$position, $company, $location, $start_date, $end_date, $is_current, $description, $sort_order, (int)$_POST['id']]);
            flash_set('success', 'Experience entry updated.');
        } else {
            $stmt = db()->prepare('INSERT INTO experience (position, company, location, start_date, end_date, is_current, description, sort_order) VALUES (?,?,?,?,?,?,?,?)');
            $stmt->execute([$position, $company, $location, $start_date, $end_date, $is_current, $description, $sort_order]);
            flash_set('success', 'Experience entry added.');
        }
        redirect($adminBase . '/experience.php');
    }
}

$items = db()->query('SELECT * FROM experience ORDER BY sort_order, id')->fetchAll();

$pageTitle = 'Experience';
$activeAdmin = 'experience';
require __DIR__ . '/layout-top.php';
?>

<div class="admin-topbar"><h1>Experience</h1></div>

<?php foreach ($errors as $err): ?><div class="alert alert-error"><?= icon('close') ?> <?= e($err) ?></div><?php endforeach; ?>

<div class="card" style="padding:24px;margin-bottom:28px;">
  <h3 style="margin-bottom:16px;"><?= $editId ? 'Edit Entry' : 'Add New Entry' ?></h3>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="<?= $editId ? 'update' : 'create' ?>">
    <?php if ($editId): ?><input type="hidden" name="id" value="<?= (int)$editId ?>"><?php endif; ?>
    <div class="form-row">
      <div class="form-group"><label>Position</label><input type="text" name="position" class="form-control" value="<?= e($editItem['position']) ?>" required></div>
      <div class="form-group"><label>Company</label><input type="text" name="company" class="form-control" value="<?= e($editItem['company']) ?>"></div>
    </div>
    <div class="grid grid-4">
      <div class="form-group"><label>Location</label><input type="text" name="location" class="form-control" value="<?= e($editItem['location']) ?>"></div>
      <div class="form-group"><label>Start Year</label><input type="text" name="start_date" class="form-control" value="<?= e($editItem['start_date']) ?>"></div>
      <div class="form-group"><label>End Year</label><input type="text" name="end_date" class="form-control" value="<?= e($editItem['end_date']) ?>"></div>
      <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="<?= (int)$editItem['sort_order'] ?>"></div>
    </div>
    <label class="check-row"><input type="checkbox" name="is_current" <?= $editItem['is_current'] ? 'checked' : '' ?>> I currently work here</label>
    <div class="form-group"><label>Description</label><textarea name="description" class="form-control"><?= e($editItem['description']) ?></textarea></div>
    <button type="submit" class="btn btn-primary"><?= icon('check') ?> <?= $editId ? 'Update' : 'Add' ?> Entry</button>
    <?php if ($editId): ?><a href="<?= e($adminBase) ?>/experience.php" class="btn btn-ghost">Cancel</a><?php endif; ?>
  </form>
</div>

<div class="table-wrap">
  <table class="admin-table">
    <thead><tr><th>Position</th><th>Company</th><th>Years</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><strong><?= e($it['position']) ?></strong></td>
          <td><?= e($it['company']) ?></td>
          <td><?= e($it['start_date']) ?> — <?= $it['is_current'] ? 'Present' : e($it['end_date']) ?></td>
          <td>
            <div class="row-actions">
              <a class="icon-btn" href="?edit=<?= (int)$it['id'] ?>"><?= icon('edit') ?></a>
              <form method="post" onsubmit="return confirm('Delete this entry?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                <button type="submit" class="icon-btn danger"><?= icon('trash') ?></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?><tr><td colspan="4" style="text-align:center;color:var(--text-muted);">No experience entries yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/layout-bottom.php'; ?>
