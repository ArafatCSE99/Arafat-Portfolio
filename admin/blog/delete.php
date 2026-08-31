<?php
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        db()->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([$id]);
        flash_set('success', 'Blog post deleted.');
    }
}

redirect($adminBase . '/blog/list.php');
