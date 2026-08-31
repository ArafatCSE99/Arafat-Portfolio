<?php
require_once __DIR__ . '/auth.php';

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $form = $_POST['form'] ?? '';

    if ($form === 'general') {
        $fields = ['site_title', 'site_tagline', 'bio_short', 'bio_long', 'email', 'phone', 'address',
                   'social_github', 'social_linkedin', 'social_facebook', 'social_twitter', 'social_instagram', 'footer_text'];
        foreach ($fields as $field) {
            update_setting($field, trim($_POST[$field] ?? ''));
        }

        try {
            $newAvatar = handle_upload('avatar', 'avatar');
            if ($newAvatar) update_setting('avatar', $newAvatar);

            $newCv = handle_upload('cv', 'cv', ['pdf']);
            if ($newCv) update_setting('cv_path', $newCv);

            flash_set('success', 'Settings updated successfully.');
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }

        if (!$errors) redirect($adminBase . '/settings.php');
    } elseif ($form === 'password') {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $stmt = db()->prepare('SELECT * FROM admin_users WHERE id = ?');
        $stmt->execute([$_SESSION['admin_id']]);
        $admin = $stmt->fetch();

        if (!password_verify($current, $admin['password_hash'])) {
            $errors[] = 'Current password is incorrect.';
        } elseif (strlen($new) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        } elseif ($new !== $confirm) {
            $errors[] = 'New password and confirmation do not match.';
        } else {
            $hash = password_hash($new, PASSWORD_BCRYPT);
            db()->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')->execute([$hash, $_SESSION['admin_id']]);
            flash_set('success', 'Password changed successfully.');
            redirect($adminBase . '/settings.php');
        }
    }
}

$settings = get_settings();

$pageTitle = 'Settings';
$activeAdmin = 'settings';
require __DIR__ . '/layout-top.php';
?>

<div class="admin-topbar"><h1>Site Settings</h1></div>

<?php foreach ($errors as $err): ?><div class="alert alert-error"><?= icon('close') ?> <?= e($err) ?></div><?php endforeach; ?>

<div class="card" style="padding:28px;max-width:800px;margin-bottom:28px;">
  <h3 style="margin-bottom:20px;">General &amp; Profile</h3>
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="form" value="general">

    <div class="form-row">
      <div class="form-group"><label>Site Title / Your Name</label><input type="text" name="site_title" class="form-control" value="<?= e($settings['site_title'] ?? '') ?>"></div>
      <div class="form-group"><label>Tagline</label><input type="text" name="site_tagline" class="form-control" value="<?= e($settings['site_tagline'] ?? '') ?>"></div>
    </div>
    <div class="form-group"><label>Short Bio</label><textarea name="bio_short" class="form-control"><?= e($settings['bio_short'] ?? '') ?></textarea></div>
    <div class="form-group"><label>Long Bio (About page)</label><textarea name="bio_long" class="form-control" style="min-height:140px;"><?= e($settings['bio_long'] ?? '') ?></textarea></div>

    <div class="form-row">
      <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= e($settings['email'] ?? '') ?>"></div>
      <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?= e($settings['phone'] ?? '') ?>"></div>
    </div>
    <div class="form-group"><label>Address</label><input type="text" name="address" class="form-control" value="<?= e($settings['address'] ?? '') ?>"></div>

    <h4 style="margin:24px 0 14px;font-size:.95rem;">Social Links</h4>
    <div class="grid grid-2">
      <div class="form-group"><label>GitHub</label><input type="url" name="social_github" class="form-control" value="<?= e($settings['social_github'] ?? '') ?>"></div>
      <div class="form-group"><label>LinkedIn</label><input type="url" name="social_linkedin" class="form-control" value="<?= e($settings['social_linkedin'] ?? '') ?>"></div>
      <div class="form-group"><label>Facebook</label><input type="url" name="social_facebook" class="form-control" value="<?= e($settings['social_facebook'] ?? '') ?>"></div>
      <div class="form-group"><label>Twitter / X</label><input type="url" name="social_twitter" class="form-control" value="<?= e($settings['social_twitter'] ?? '') ?>"></div>
      <div class="form-group"><label>Instagram</label><input type="url" name="social_instagram" class="form-control" value="<?= e($settings['social_instagram'] ?? '') ?>"></div>
    </div>

    <div class="form-group"><label>Footer Text</label><input type="text" name="footer_text" class="form-control" value="<?= e($settings['footer_text'] ?? '') ?>"></div>

    <h4 style="margin:24px 0 14px;font-size:.95rem;">Files</h4>
    <div class="grid grid-2">
      <div class="form-group">
        <label>Profile Photo</label>
        <img src="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e($settings['avatar'] ?? '') ?>" style="width:100px;height:100px;object-fit:cover;border-radius:12px;margin-bottom:10px;display:block;">
        <input type="file" name="avatar" class="form-control" accept="image/*">
      </div>
      <div class="form-group">
        <label>CV / Resume (PDF)</label>
        <p style="margin-bottom:10px;"><a href="<?= e(rtrim(SITE_URL,'/')) ?>/<?= e($settings['cv_path'] ?? '') ?>" target="_blank" class="btn btn-outline btn-sm"><?= icon('download') ?> Current CV</a></p>
        <input type="file" name="cv" class="form-control" accept="application/pdf">
      </div>
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top:10px;"><?= icon('check') ?> Save Settings</button>
  </form>
</div>

<div class="card" style="padding:28px;max-width:500px;">
  <h3 style="margin-bottom:20px;">Change Password</h3>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="form" value="password">
    <div class="form-group"><label>Current Password</label><input type="password" name="current_password" class="form-control" required></div>
    <div class="form-group"><label>New Password</label><input type="password" name="new_password" class="form-control" required minlength="8"></div>
    <div class="form-group"><label>Confirm New Password</label><input type="password" name="confirm_password" class="form-control" required minlength="8"></div>
    <button type="submit" class="btn btn-primary"><?= icon('check') ?> Change Password</button>
  </form>
</div>

<?php require __DIR__ . '/layout-bottom.php'; ?>
