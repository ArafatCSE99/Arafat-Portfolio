<?php
require_once __DIR__ . '/../auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = ['title' => '', 'excerpt' => '', 'content' => '', 'cover_image' => '', 'category' => '', 'tags' => '', 'published' => 1];
$isEdit = false;

if ($id) {
    $stmt = db()->prepare('SELECT * FROM blog_posts WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) {
        $post = $found;
        $isEdit = true;
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $post['title'] = trim($_POST['title'] ?? '');
        $post['excerpt'] = trim($_POST['excerpt'] ?? '');
        $post['content'] = trim($_POST['content'] ?? '');
        $post['category'] = trim($_POST['category'] ?? '');
        $post['tags'] = trim($_POST['tags'] ?? '');
        $post['published'] = isset($_POST['published']) ? 1 : 0;

        if ($post['title'] === '') {
            $errors[] = 'Title is required.';
        }

        if (!$errors) {
            try {
                $newImage = handle_upload('cover_image', 'blog');
                if ($newImage) {
                    $post['cover_image'] = $newImage;
                } elseif (!$isEdit && !$post['cover_image']) {
                    $post['cover_image'] = 'assets/images/blog-placeholder-1.svg';
                }
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (!$errors) {
            if ($isEdit) {
                $stmt = db()->prepare('UPDATE blog_posts SET title=?, excerpt=?, content=?, cover_image=?, category=?, tags=?, published=? WHERE id=?');
                $stmt->execute([$post['title'], $post['excerpt'], $post['content'], $post['cover_image'], $post['category'], $post['tags'], $post['published'], $id]);
                flash_set('success', 'Blog post updated successfully.');
            } else {
                $slug = unique_slug('blog_posts', $post['title']);
                $stmt = db()->prepare('INSERT INTO blog_posts (title, slug, excerpt, content, cover_image, category, tags, published) VALUES (?,?,?,?,?,?,?,?)');
                $stmt->execute([$post['title'], $slug, $post['excerpt'], $post['content'], $post['cover_image'], $post['category'], $post['tags'], $post['published']]);
                flash_set('success', 'Blog post created successfully.');
            }
            redirect($adminBase . '/blog/list.php');
        }
    }
}

$pageTitle = $isEdit ? 'Edit Blog Post' : 'Add Blog Post';
$activeAdmin = 'blog';
require __DIR__ . '/../layout-top.php';
?>

<div class="admin-topbar">
  <h1><?= e($pageTitle) ?></h1>
  <a href="<?= e($adminBase) ?>/blog/list.php" class="btn btn-outline">&larr; Back to Blog Posts</a>
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
        <input type="text" name="title" class="form-control" value="<?= e($post['title']) ?>" required>
      </div>
      <div class="form-group">
        <label>Category</label>
        <input type="text" name="category" class="form-control" value="<?= e($post['category']) ?>" placeholder="e.g. Development">
      </div>
    </div>
    <div class="form-group">
      <label>Excerpt (short summary)</label>
      <input type="text" name="excerpt" class="form-control" value="<?= e($post['excerpt']) ?>">
    </div>
    <div class="form-group">
      <label>Content (HTML allowed — use &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt; etc.)</label>
      <textarea name="content" class="form-control" style="min-height:280px;font-family:Consolas,monospace;font-size:.88rem;"><?= e($post['content']) ?></textarea>
    </div>
    <div class="form-group">
      <label>Tags (comma-separated)</label>
      <input type="text" name="tags" class="form-control" value="<?= e($post['tags']) ?>" placeholder="php, tutorial">
    </div>
    <div class="form-group">
      <label>Cover Image</label>
      <?php if (!empty($post['cover_image'])): ?>
        <img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e($post['cover_image']) ?>" style="width:160px;border-radius:10px;margin-bottom:10px;display:block;">
      <?php endif; ?>
      <input type="file" name="cover_image" class="form-control" accept="image/*">
    </div>
    <label class="check-row"><input type="checkbox" name="published" <?= $post['published'] ? 'checked' : '' ?>> Published (visible on the site)</label>
    <button type="submit" class="btn btn-primary" style="margin-top:12px;"><?= icon('check') ?> Save Post</button>
  </form>
</div>

<?php require __DIR__ . '/../layout-bottom.php'; ?>
