<?php
require_once __DIR__ . '/../includes/functions.php';
$base = rtrim(SITE_URL, '/');

if (!empty($_SESSION['admin_id'])) {
    redirect($base . '/admin/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Your session expired. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = db()->prepare('SELECT * FROM admin_users WHERE username = ?');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect($base . '/admin/dashboard.php');
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

$meta = seo_meta('Admin Login');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($meta['title']) ?></title>
<link rel="stylesheet" href="<?= e($base) ?>/assets/css/style.css">
<link rel="stylesheet" href="<?= e($base) ?>/assets/css/admin-theme.css">
</head>
<body>
<div class="admin-login-wrap">
  <div class="card admin-login-card">
    <div style="text-align:center;margin-bottom:28px;">
      <span class="brand-mark" style="margin:0 auto 14px;display:flex;"><?= e(strtoupper(substr(setting('site_title', 'A'), 0, 1))) ?></span>
      <h1 style="font-size:1.4rem;"><?= e(setting('site_title', 'Portfolio')) ?> Admin</h1>
      <p style="color:var(--text-muted);font-size:.9rem;">Sign in to manage <?= e(setting('site_title', 'your')) ?>'s portfolio content.</p>
    </div>
    <?php if ($error): ?>
      <div class="alert alert-error"><?= icon('close') ?> <?= e($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control" required autofocus>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block"><?= icon('user') ?> Sign In</button>
    </form>
    <p style="text-align:center;margin-top:20px;"><a href="<?= e($base) ?>/" style="color:var(--text-muted);font-size:.85rem;">&larr; Back to site</a></p>
  </div>
</div>
</body>
</html>
