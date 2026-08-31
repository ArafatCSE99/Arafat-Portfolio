<?php
require_once __DIR__ . '/functions.php';
$base = rtrim(SITE_URL, '/');
?>
</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <a href="<?= e($base) ?>/" class="brand" style="margin-bottom:14px;">
          <span class="brand-mark"><?= e(strtoupper(substr(setting('site_title', 'A'), 0, 1))) ?></span>
          <span><?= e(setting('site_title', 'Portfolio')) ?></span>
        </a>
        <p><?= e(setting_t('bio_short')) ?></p>
        <div class="social-row" style="margin-top:18px;">
          <?php foreach (['github', 'linkedin', 'facebook', 'twitter', 'instagram'] as $s): ?>
            <?php $url = setting('social_' . $s); if (!$url) continue; ?>
            <a class="social-btn" href="<?= e($url) ?>" target="_blank" rel="noopener" aria-label="<?= e($s) ?>"><?= icon($s) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <div>
        <h4><?= e(t('footer.quick_links')) ?></h4>
        <ul class="footer-links">
          <li><a href="<?= e($base) ?>/about.php"><?= e(t('footer.about_me')) ?></a></li>
          <li><a href="<?= e($base) ?>/projects.php"><?= e(t('nav.projects')) ?></a></li>
          <li><a href="<?= e($base) ?>/blog.php"><?= e(t('nav.blog')) ?></a></li>
          <li><a href="<?= e($base) ?>/tools/index.php"><?= e(t('footer.free_tools')) ?></a></li>
        </ul>
      </div>
      <div>
        <h4><?= e(t('footer.get_in_touch')) ?></h4>
        <ul class="footer-links">
          <li><a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></li>
          <li><a href="tel:<?= e(setting('phone')) ?>"><?= e(setting('phone')) ?></a></li>
          <li><span><?= e(setting('address')) ?></span></li>
          <li><a href="<?= e($base) ?>/contact.php"><?= e(t('footer.contact_form')) ?></a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span><?= e(setting_t('footer_text')) ?></span>
      <span><?= e(t('footer.built_with')) ?></span>
      <a href="#top" class="footer-top-link" id="backToTopBtn"><?= icon('arrow-up') ?> <?= e(t('footer.back_to_top')) ?></a>
    </div>
  </div>
</footer>

<script src="<?= e(asset_url('assets/js/main.js')) ?>"></script>
</body>
</html>
