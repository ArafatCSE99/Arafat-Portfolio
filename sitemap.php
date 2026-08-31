<?php
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/xml; charset=utf-8');

$base = rtrim(SITE_URL, '/');
$staticPages = ['', 'about.php', 'services.php', 'projects.php', 'blog.php', 'faq.php', 'contact.php', 'tools/index.php'];

$projects = db()->query('SELECT slug, created_at FROM projects ORDER BY created_at DESC')->fetchAll();
$posts = db()->query('SELECT slug, updated_at FROM blog_posts WHERE published = 1 ORDER BY updated_at DESC')->fetchAll();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($staticPages as $page): ?>
  <url><loc><?= e($base . '/' . $page) ?></loc><changefreq>weekly</changefreq></url>
<?php endforeach; ?>
<?php foreach ($projects as $p): ?>
  <url><loc><?= e($base . '/project/' . $p['slug']) ?></loc><lastmod><?= e(date('Y-m-d', strtotime($p['created_at']))) ?></lastmod></url>
<?php endforeach; ?>
<?php foreach ($posts as $p): ?>
  <url><loc><?= e($base . '/blog/' . $p['slug']) ?></loc><lastmod><?= e(date('Y-m-d', strtotime($p['updated_at']))) ?></lastmod></url>
<?php endforeach; ?>
</urlset>
