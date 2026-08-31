<?php
/**
 * Site-wide configuration constants.
 * Copy this file to config.php and fill in your own values.
 * config.php is gitignored — it never gets committed.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');

// Base URL used for canonical links, sitemap, and Open Graph tags.
define('SITE_URL', 'http://localhost/Arafat');

define('UPLOADS_DIR', __DIR__ . '/../uploads');
define('UPLOADS_URL', 'uploads');

// Max upload size for admin file uploads (bytes)
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
date_default_timezone_set('Asia/Dhaka');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
