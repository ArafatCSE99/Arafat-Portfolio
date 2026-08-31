<?php
require_once __DIR__ . '/../auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$project = ['title' => '', 'summary' => '', 'description' => '', 'cover_image' => '', 'demo_url' => '', 'github_url' => '', 'technologies' => '', 'category' => '', 'featured' => 0];
$isEdit = false;

if ($id) {
    $stmt = db()->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) {
        $project = $found;
        $isEdit = true;
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $project['title'] = trim($_POST['title'] ?? '');
        $project['summary'] = trim($_POST['summary'] ?? '');
        $project['description'] = trim($_POST['description'] ?? '');
        $project['demo_url'] = trim($_POST['demo_url'] ?? '');
        $project['github_url'] = trim($_POST['github_url'] ?? '');
        $project['technologies'] = trim($_POST['technologies'] ?? '');
        $project['category'] = trim($_POST['category'] ?? '');
        $project['featured'] = isset($_POST['featured']) ? 1 : 0;

        if ($project['title'] === '') {
            $errors[] = 'Title is required.';
        }

        if (!$errors) {
            try {
                $newImage = handle_upload('cover_image', 'projects');
                if ($newImage) {
                    $project['cover_image'] = $newImage;
                } elseif (!$isEdit && !$project['cover_image']) {
                    $project['cover_image'] = 'assets/images/project-placeholder-1.svg';
                }
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (!$errors) {
            if ($isEdit) {
                $stmt = db()->prepare('UPDATE projects SET title=?, summary=?, description=?, cover_image=?, demo_url=?, github_url=?, technologies=?, category=?, featured=? WHERE id=?');
                $stmt->execute([$project['title'], $project['summary'], $project['description'], $project['cover_image'], $project['demo_url'], $project['github_url'], $project['technologies'], $project['category'], $project['featured'], $id]);
                flash_set('success', 'Project updated successfully.');
            } else {
                $slug = unique_slug('projects', $project['title']);
                $stmt = db()->prepare('INSERT INTO projects (title, slug, summary, description, cover_image, demo_url, github_url, technologies, category, featured) VALUES (?,?,?,?,?,?,?,?,?,?)');
                $stmt->execute([$project['title'], $slug, $project['summary'], $project['description'], $project['cover_image'], $project['demo_url'], $project['github_url'], $project['technologies'], $project['category'], $project['featured']]);
                flash_set('success', 'Project created successfully.');
            }
            redirect($adminBase . '/projects/list.php');
        }
    }
}

$pageTitle = $isEdit ? 'Edit Project' : 'Add Project';
$activeAdmin = 'projects';
require __DIR__ . '/../layout-top.php';
?>

<div class="admin-topbar">
  <h1><?= e($pageTitle) ?></h1>
  <a href="<?= e($adminBase) ?>/projects/list.php" class="btn btn-outline">&larr; Back to Projects</a>
</div>

<?php foreach ($errors as $err): ?>
  <div class="alert alert-error"><?= icon('close') ?> <?= e($err) ?></div>
<?php endforeach; ?>

<div class="card" style="padding:28px;max-width:800px;">
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-row">
      <div class="form-group">
        <label>Title *</label>
        <input type="text" name="title" class="form-control" value="<?= e($project['title']) ?>" required>
      </div>
      <div class="form-group">
        <label>Category</label>
        <input type="text" name="category" class="form-control" value="<?= e($project['category']) ?>" placeholder="e.g. Web App">
      </div>
    </div>
    <div class="form-group">
      <label>Summary (short, ~1 sentence)</label>
      <input type="text" name="summary" class="form-control" value="<?= e($project['summary']) ?>">
    </div>
    <div class="form-group">
      <label>Full Description</label>
      <textarea name="description" class="form-control" style="min-height:160px;"><?= e($project['description']) ?></textarea>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Demo URL</label>
        <input type="url" name="demo_url" class="form-control" value="<?= e($project['demo_url']) ?>">
      </div>
      <div class="form-group">
        <label>GitHub URL</label>
        <input type="url" name="github_url" class="form-control" value="<?= e($project['github_url']) ?>">
      </div>
    </div>
    <div class="form-group">
      <label>Technologies (comma-separated)</label>
      <input type="text" name="technologies" class="form-control" value="<?= e($project['technologies']) ?>" placeholder="PHP, MySQL, JavaScript">
    </div>
    <div class="form-group">
      <label>Cover Image</label>
      <?php if (!empty($project['cover_image'])): ?>
        <img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e($project['cover_image']) ?>" style="width:160px;border-radius:10px;margin-bottom:10px;display:block;">
      <?php endif; ?>
      <input type="file" name="cover_image" class="form-control" accept="image/*">
    </div>
    <label class="check-row"><input type="checkbox" name="featured" <?= $project['featured'] ? 'checked' : '' ?>> Feature this project on the homepage</label>
    <button type="submit" class="btn btn-primary" style="margin-top:12px;"><?= icon('check') ?> Save Project</button>
  </form>
</div>

<?php require __DIR__ . '/../layout-bottom.php'; ?>
