<?php
$footerCounts = [
    'projects' => db()->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
    'posts' => db()->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn(),
    'unread' => db()->query('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn(),
];
?>
    <footer class="admin-footer">
      <span class="admin-footer-brand" title="Content management panel, built in-house by <?= e(setting('site_title', 'Portfolio')) ?>."><?= icon('code') ?> <strong><?= e(setting('site_title', 'Portfolio')) ?></strong> &mdash; <?= e(setting('site_tagline', 'Web Developer')) ?> <span class="admin-footer-sep">·</span> Admin Panel &copy; <?= e(date('Y')) ?></span>
      <div class="admin-footer-stats">
        <a href="<?= e($adminBase) ?>/projects/list.php"><?= icon('briefcase') ?> <?= (int)$footerCounts['projects'] ?></a>
        <a href="<?= e($adminBase) ?>/blog/list.php"><?= icon('edit') ?> <?= (int)$footerCounts['posts'] ?></a>
        <a href="<?= e($adminBase) ?>/messages.php"><?= icon('mail') ?> <?= (int)$footerCounts['unread'] ?><?php if ((int)$footerCounts['unread'] > 0): ?><span class="admin-footer-dot"></span><?php endif; ?></a>
      </div>
      <a href="<?= e(rtrim(SITE_URL,'/')) ?>/" target="_blank" class="admin-footer-view"><?= icon('external-link') ?> View live site</a>
    </footer>
  </main>
</div>
<?php if (!empty($pageScripts)): foreach ($pageScripts as $src): ?>
<script src="<?= e(asset_url($src)) ?>"></script>
<?php endforeach; endif; ?>
<script src="<?= e(asset_url('assets/js/main.js')) ?>"></script>
</body>
</html>
