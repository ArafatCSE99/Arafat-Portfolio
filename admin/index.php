<?php
require_once __DIR__ . '/../includes/functions.php';
$base = rtrim(SITE_URL, '/');
redirect($base . '/admin/' . (!empty($_SESSION['admin_id']) ? 'dashboard.php' : 'login.php'));
