<?php
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        db()->prepare('DELETE FROM projects WHERE id = ?')->execute([$id]);
        flash_set('success', 'Project deleted.');
    }
}

redirect($adminBase . '/projects/list.php');
