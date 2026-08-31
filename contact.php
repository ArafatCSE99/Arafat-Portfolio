<?php
require_once __DIR__ . '/includes/functions.php';
$base = rtrim(SITE_URL, '/');

$errors = [];
$old = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name'] = trim($_POST['name'] ?? '');
    $old['email'] = trim($_POST['email'] ?? '');
    $old['subject'] = trim($_POST['subject'] ?? '');
    $old['message'] = trim($_POST['message'] ?? '');
    $honeypot = trim($_POST['website'] ?? '');

    if (!csrf_verify()) {
        $errors[] = t('contact.err_session');
    } elseif ($honeypot !== '') {
        // Silently drop bot submissions without revealing the honeypot.
        redirect($base . '/contact.php?sent=1');
    } else {
        if ($old['name'] === '' || mb_strlen($old['name']) > 150) {
            $errors[] = t('contact.err_name');
        }
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = t('contact.err_email');
        }
        if ($old['message'] === '' || mb_strlen($old['message']) > 5000) {
            $errors[] = t('contact.err_message');
        }

        if (!$errors) {
            $stmt = db()->prepare('INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
            $stmt->execute([$old['name'], $old['email'], $old['subject'], $old['message']]);
            redirect($base . '/contact.php?sent=1');
        }
    }
}

$meta = seo_meta('Contact', 'Get in touch with me for project inquiries or collaborations.');
$activePage = 'contact';

require __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <span><?= e(t('nav.contact')) ?></span></div>
    <span class="eyebrow"><?= e(t('contact.eyebrow')) ?></span>
    <h1 class="section-title"><?= e(t('contact.heading')) ?></h1>
    <p class="section-desc"><?= e(t('contact.desc')) ?></p>
  </div>
</div>

<section class="section" style="padding-top:0;">
  <div class="container contact-grid">
    <div class="reveal">
      <div class="contact-info-item">
        <div class="contact-info-icon"><?= icon('mail') ?></div>
        <div><h4><?= e(t('common.email')) ?></h4><a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></div>
      </div>
      <div class="contact-info-item">
        <div class="contact-info-icon"><?= icon('phone') ?></div>
        <div><h4><?= e(t('common.phone')) ?></h4><a href="tel:<?= e(setting('phone')) ?>"><?= e(setting('phone')) ?></a></div>
      </div>
      <div class="contact-info-item">
        <div class="contact-info-icon"><?= icon('map-pin') ?></div>
        <div><h4><?= e(t('common.location')) ?></h4><p><?= e(setting('address')) ?></p></div>
      </div>
      <div class="social-row" style="margin-top:8px;">
        <?php foreach (['github', 'linkedin', 'facebook', 'twitter', 'instagram'] as $s): ?>
          <?php $url = setting('social_' . $s); if (!$url) continue; ?>
          <a class="social-btn" href="<?= e($url) ?>" target="_blank" rel="noopener" aria-label="<?= e($s) ?>"><?= icon($s) ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="card reveal" style="padding:36px;">
      <?php if (isset($_GET['sent'])): ?>
        <div class="alert alert-success" data-autohide><?= icon('check') ?> <?= e(t('contact.success')) ?></div>
      <?php endif; ?>
      <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= icon('close') ?> <?= e($err) ?></div>
      <?php endforeach; ?>

      <form method="post" action="<?= e($base) ?>/contact.php" novalidate>
        <?= csrf_field() ?>
        <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off">
        <div class="form-row">
          <div class="form-group">
            <label for="name"><?= e(t('contact.name')) ?></label>
            <input type="text" id="name" name="name" class="form-control" value="<?= e($old['name']) ?>" required>
          </div>
          <div class="form-group">
            <label for="email"><?= e(t('contact.email')) ?></label>
            <input type="email" id="email" name="email" class="form-control" value="<?= e($old['email']) ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label for="subject"><?= e(t('contact.subject')) ?></label>
          <input type="text" id="subject" name="subject" class="form-control" value="<?= e($old['subject']) ?>">
        </div>
        <div class="form-group">
          <label for="message"><?= e(t('contact.message')) ?></label>
          <textarea id="message" name="message" class="form-control" required><?= e($old['message']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><?= icon('send') ?> <?= e(t('contact.send')) ?></button>
      </form>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
