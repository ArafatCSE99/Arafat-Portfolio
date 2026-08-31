<?php
require_once __DIR__ . '/auth.php';

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editItem = ['name' => '', 'category' => '', 'proficiency' => 80, 'icon_key' => 'code', 'sort_order' => 0];
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM skills WHERE id = ?');
    $stmt->execute([$editId]);
    $found = $stmt->fetch();
    if ($found) $editItem = $found;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        db()->prepare('DELETE FROM skills WHERE id = ?')->execute([(int)$_POST['id']]);
        flash_set('success', 'Skill deleted.');
        redirect($adminBase . '/skills.php');
    }

    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $proficiency = max(0, min(100, (int)($_POST['proficiency'] ?? 0)));
    $icon_key = trim($_POST['icon_key'] ?? 'code');
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if (!$errors) {
        if ($action === 'update') {
            $stmt = db()->prepare('UPDATE skills SET name=?, category=?, proficiency=?, icon_key=?, sort_order=? WHERE id=?');
            $stmt->execute([$name, $category, $proficiency, $icon_key, $sort_order, (int)$_POST['id']]);
            flash_set('success', 'Skill updated.');
        } else {
            $stmt = db()->prepare('INSERT INTO skills (name, category, proficiency, icon_key, sort_order) VALUES (?,?,?,?,?)');
            $stmt->execute([$name, $category, $proficiency, $icon_key, $sort_order]);
            flash_set('success', 'Skill added.');
        }
        redirect($adminBase . '/skills.php');
    }
}

$items = db()->query('SELECT * FROM skills ORDER BY sort_order, id')->fetchAll();
$iconOptions = ['code', 'server', 'database', 'git', 'palette', 'settings', 'briefcase'];

$pageTitle = 'Skills';
$activeAdmin = 'skills';
require __DIR__ . '/layout-top.php';
?>

<div class="admin-topbar"><h1>Skills</h1></div>

<?php foreach ($errors as $err): ?><div class="alert alert-error"><?= icon('close') ?> <?= e($err) ?></div><?php endforeach; ?>

<div class="card" style="padding:24px;margin-bottom:28px;">
  <h3 style="margin-bottom:16px;"><?= $editId ? 'Edit Skill' : 'Add New Skill' ?></h3>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="<?= $editId ? 'update' : 'create' ?>">
    <?php if ($editId): ?><input type="hidden" name="id" value="<?= (int)$editId ?>"><?php endif; ?>
    <div class="grid grid-4">
      <div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" value="<?= e($editItem['name']) ?>" required></div>
      <div class="form-group"><label>Category</label><input type="text" name="category" class="form-control" value="<?= e($editItem['category']) ?>" placeholder="Frontend"></div>
      <div class="form-group"><label>Proficiency %</label><input type="number" name="proficiency" class="form-control" min="0" max="100" value="<?= (int)$editItem['proficiency'] ?>"></div>
      <div class="form-group"><label>Icon</label>
        <select name="icon_key" class="form-control">
          <?php foreach ($iconOptions as $ic): ?><option value="<?= e($ic) ?>" <?= $editItem['icon_key'] === $ic ? 'selected' : '' ?>><?= e($ic) ?></option><?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-group" style="max-width:160px;"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="<?= (int)$editItem['sort_order'] ?>"></div>
    <button type="submit" class="btn btn-primary"><?= icon('check') ?> <?= $editId ? 'Update' : 'Add' ?> Skill</button>
    <?php if ($editId): ?><a href="<?= e($adminBase) ?>/skills.php" class="btn btn-ghost">Cancel</a><?php endif; ?>
  </form>
</div>

<div class="table-wrap">
  <table class="admin-table">
    <thead><tr><th>Name</th><th>Category</th><th>Proficiency</th><th>Sort</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><strong><?= e($it['name']) ?></strong></td>
          <td><?= e($it['category']) ?></td>
          <td><?= (int)$it['proficiency'] ?>%</td>
          <td><?= (int)$it['sort_order'] ?></td>
          <td>
            <div class="row-actions">
              <a class="icon-btn" href="?edit=<?= (int)$it['id'] ?>"><?= icon('edit') ?></a>
              <form method="post" onsubmit="return confirm('Delete this skill?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                <button type="submit" class="icon-btn danger"><?= icon('trash') ?></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?><tr><td colspan="5" style="text-align:center;color:var(--text-muted);">No skills yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/layout-bottom.php'; ?>
