<?php
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['admin_id'])) {
    redirect(rtrim(SITE_URL, '/') . '/admin/login.php');
}

$adminBase = rtrim(SITE_URL, '/') . '/admin';
