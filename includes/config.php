<?php
/**
 * Site-wide configuration constants.
 * Adjust DB credentials here if your XAMPP MySQL setup differs from the defaults.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'arafat_portfolio');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL used for canonical links, sitemap, and Open Graph tags.
// Update this if the site is deployed outside of /Arafat on localhost.
define('SITE_URL', 'http://localhost/Arafat');

define('UPLOADS_DIR', __DIR__ . '/../uploads');
define('UPLOADS_URL', 'uploads');

// Max upload size for admin file uploads (bytes)
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Asia/Dhaka');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
