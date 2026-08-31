<?php
require_once __DIR__ . '/auth.php';

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editItem = ['title' => '', 'issuer' => '', 'issue_date' => '', 'credential_url' => '', 'image' => '', 'sort_order' => 0];
if ($editId) {
    $stmt = db()->prepare('SELECT * FROM certificates WHERE id = ?');
    $stmt->execute([$editId]);
    $found = $stmt->fetch();
    if ($found) $editItem = $found;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        db()->prepare('DELETE FROM certificates WHERE id = ?')->execute([(int)$_POST['id']]);
        flash_set('success', 'Certificate deleted.');
        redirect($adminBase . '/certificates.php');
    }

    $title = trim($_POST['title'] ?? '');
    $issuer = trim($_POST['issuer'] ?? '');
    $issue_date = trim($_POST['issue_date'] ?? '');
    $credential_url = trim($_POST['credential_url'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $image = $editItem['image'] ?: 'assets/images/cert-placeholder-1.svg';

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    if (!$errors) {
        try {
            $newImage = handle_upload('image', 'certificates');
            if ($newImage) $image = $newImage;
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }

    if (!$errors) {
        if ($action === 'update') {
            $stmt = db()->prepare('UPDATE certificates SET title=?, issuer=?, issue_date=?, credential_url=?, image=?, sort_order=? WHERE id=?');
            $stmt->execute([$title, $issuer, $issue_date, $credential_url, $image, $sort_order, (int)$_POST['id']]);
            flash_set('success', 'Certificate updated.');
        } else {
            $stmt = db()->prepare('INSERT INTO certificates (title, issuer, issue_date, credential_url, image, sort_order) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$title, $issuer, $issue_date, $credential_url, $image, $sort_order]);
            flash_set('success', 'Certificate added.');
        }
        redirect($adminBase . '/certificates.php');
    }
}

$items = db()->query('SELECT * FROM certificates ORDER BY sort_order, id')->fetchAll();

$pageTitle = 'Certificates';
$activeAdmin = 'certificates';
require __DIR__ . '/layout-top.php';
?>

<div class="admin-topbar"><h1>Certificates</h1></div>

<?php foreach ($errors as $err): ?><div class="alert alert-error"><?= icon('close') ?> <?= e($err) ?></div><?php endforeach; ?>

<div class="card" style="padding:24px;margin-bottom:28px;">
  <h3 style="margin-bottom:16px;"><?= $editId ? 'Edit Certificate' : 'Add New Certificate' ?></h3>
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="<?= $editId ? 'update' : 'create' ?>">
    <?php if ($editId): ?><input type="hidden" name="id" value="<?= (int)$editId ?>"><?php endif; ?>
    <div class="form-row">
      <div class="form-group"><label>Title</label><input type="text" name="title" class="form-control" value="<?= e($editItem['title']) ?>" required></div>
      <div class="form-group"><label>Issuer</label><input type="text" name="issuer" class="form-control" value="<?= e($editItem['issuer']) ?>"></div>
    </div>
    <div class="grid grid-3">
      <div class="form-group"><label>Issue Date / Year</label><input type="text" name="issue_date" class="form-control" value="<?= e($editItem['issue_date']) ?>"></div>
      <div class="form-group"><label>Credential URL</label><input type="url" name="credential_url" class="form-control" value="<?= e($editItem['credential_url']) ?>"></div>
      <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="<?= (int)$editItem['sort_order'] ?>"></div>
    </div>
    <div class="form-group">
      <label>Badge / Certificate Image</label>
      <?php if (!empty($editItem['image'])): ?>
        <img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e($editItem['image']) ?>" style="width:120px;border-radius:10px;margin-bottom:10px;display:block;">
      <?php endif; ?>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary"><?= icon('check') ?> <?= $editId ? 'Update' : 'Add' ?> Certificate</button>
    <?php if ($editId): ?><a href="<?= e($adminBase) ?>/certificates.php" class="btn btn-ghost">Cancel</a><?php endif; ?>
  </form>
</div>

<div class="table-wrap">
  <table class="admin-table">
    <thead><tr><th>Title</th><th>Issuer</th><th>Date</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><strong><?= e($it['title']) ?></strong></td>
          <td><?= e($it['issuer']) ?></td>
          <td><?= e($it['issue_date']) ?></td>
          <td>
            <div class="row-actions">
              <a class="icon-btn" href="?edit=<?= (int)$it['id'] ?>"><?= icon('edit') ?></a>
              <form method="post" onsubmit="return confirm('Delete this certificate?');">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                <button type="submit" class="icon-btn danger"><?= icon('trash') ?></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$items): ?><tr><td colspan="4" style="text-align:center;color:var(--text-muted);">No certificates yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/layout-bottom.php'; ?>
