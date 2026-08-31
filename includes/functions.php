<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/lang.php';

/* ---------------------------------------------------------------
 * General helpers
 * ------------------------------------------------------------- */

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Appends a cache-busting ?v=<mtime> query string to a site-relative asset
 * path (e.g. "assets/js/main.js"), so browsers fetch a fresh copy whenever
 * the file changes instead of serving a stale cached version.
 */
function asset_url(string $relativePath): string
{
    $base = rtrim(SITE_URL, '/');
    $diskPath = __DIR__ . '/../' . $relativePath;
    $version = is_file($diskPath) ? filemtime($diskPath) : time();
    return $base . '/' . $relativePath . '?v=' . $version;
}

function slugify(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = trim(iconv('UTF-8', 'ASCII//TRANSLIT', $text), '-');
    $text = strtolower(preg_replace('~[^-\w]+~', '', $text));
    return $text !== '' ? $text : 'n-a';
}

function unique_slug(string $table, string $base): string
{
    $slug = slugify($base);
    $original = $slug;
    $i = 2;
    $stmt = db()->prepare("SELECT COUNT(*) FROM {$table} WHERE slug = ?");
    while (true) {
        $stmt->execute([$slug]);
        if ((int)$stmt->fetchColumn() === 0) {
            return $slug;
        }
        $slug = $original . '-' . $i++;
    }
}

function truncate(string $text, int $length = 120): string
{
    $text = trim(strip_tags($text));
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '…';
}

function format_date(?string $date, string $format = 'M j, Y'): string
{
    if (!$date) {
        return '';
    }
    $ts = strtotime($date);
    return $ts ? date($format, $ts) : '';
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

/* ---------------------------------------------------------------
 * Site settings (key/value store)
 * ------------------------------------------------------------- */

function get_settings(): array
{
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        foreach (db()->query('SELECT setting_key, setting_value, setting_value_bn FROM site_settings') as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
            $settings[$row['setting_key'] . '_bn'] = $row['setting_value_bn'];
        }
    }
    return $settings;
}

function setting(string $key, string $default = ''): string
{
    $settings = get_settings();
    return $settings[$key] ?? $default;
}

/** Bangla-aware setting lookup — falls back to the English value if no translation was entered. */
function setting_t(string $key, string $default = ''): string
{
    $settings = get_settings();
    if (lang() === 'bn' && !empty($settings[$key . '_bn'])) {
        return $settings[$key . '_bn'];
    }
    return $settings[$key] ?? $default;
}

function update_setting(string $key, string $value, ?string $valueBn = null): void
{
    if ($valueBn === null) {
        $stmt = db()->prepare(
            'INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
        );
        $stmt->execute([$key, $value]);
    } else {
        $stmt = db()->prepare(
            'INSERT INTO site_settings (setting_key, setting_value, setting_value_bn) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_value_bn = VALUES(setting_value_bn)'
        );
        $stmt->execute([$key, $value, $valueBn]);
    }
}

/* ---------------------------------------------------------------
 * CSRF protection
 * ------------------------------------------------------------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): bool
{
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

/* ---------------------------------------------------------------
 * SEO meta helper
 * ------------------------------------------------------------- */

function seo_meta(string $title, string $description = '', string $image = '', string $type = 'website'): array
{
    $siteTitle = setting('site_title', 'Portfolio');
    return [
        'title' => $title ? "{$title} — {$siteTitle}" : $siteTitle,
        'description' => $description ?: setting_t('bio_short'),
        'image' => $image ? (SITE_URL . '/' . ltrim($image, '/')) : (SITE_URL . '/' . setting('avatar')),
        'type' => $type,
        'url' => SITE_URL . ($_SERVER['REQUEST_URI'] ?? ''),
    ];
}

/* ---------------------------------------------------------------
 * Inline SVG icon helper (no external icon font / CDN dependency)
 * ------------------------------------------------------------- */

function icon(string $name, string $class = 'icon'): string
{
    $icons = [
        'code' => '<path d="M8 3 2 9l6 6M16 3l6 6-6 6M12 2 8 20"/>',
        'server' => '<rect x="2" y="3" width="20" height="7" rx="1.5"/><rect x="2" y="14" width="20" height="7" rx="1.5"/><path d="M6 6.5h.01M6 17.5h.01"/>',
        'database' => '<ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.66 3.58 3 8 3s8-1.34 8-3V5"/><path d="M4 12c0 1.66 3.58 3 8 3s8-1.34 8-3"/>',
        'git' => '<circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><circle cx="18" cy="9" r="2.5"/><path d="M6 8.5v7M6 8c0 4 4 4 10 4"/>',
        'palette' => '<circle cx="12" cy="12" r="9.5"/><circle cx="8" cy="10" r="1.2" fill="currentColor"/><circle cx="12" cy="7" r="1.2" fill="currentColor"/><circle cx="16" cy="10" r="1.2" fill="currentColor"/><circle cx="15" cy="15" r="1.2" fill="currentColor"/><path d="M12 21.5A2.5 2.5 0 0 1 9.5 19c0-1 .7-1.6.7-2.4 0-.9-.8-1.6-2-1.6h-1a3 3 0 0 1-3-3 9.5 9.5 0 1 1 8.5 9Z"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.14.36.5.7 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/>',
        'github' => '<path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.46-1.16-1.11-1.47-1.11-1.47-.9-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.53 2.36 1.09 2.93.83.09-.65.35-1.09.63-1.34-2.22-.25-4.56-1.11-4.56-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.65 0 0 .84-.27 2.75 1.02a9.4 9.4 0 0 1 5 0c1.91-1.3 2.75-1.02 2.75-1.02.55 1.38.2 2.4.1 2.65.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.68-4.57 4.93.36.31.68.92.68 1.85V21c0 .27.18.58.69.48A10 10 0 0 0 12 2Z"/>',
        'linkedin' => '<rect x="2" y="2" width="20" height="20" rx="3"/><path d="M7 10v7M7 7v.01M12 17v-4.5c0-1.4 1-2.5 2.5-2.5S17 11.1 17 12.5V17M12 10v7" stroke-linecap="round"/>',
        'facebook' => '<path d="M14 9h3V6h-3c-2 0-3.5 1.5-3.5 3.5V12H8v3h2.5v6h3v-6H16l.5-3h-3V9.7c0-.5.3-.7.5-.7Z"/>',
        'twitter' => '<path d="M22 5.9c-.7.3-1.5.6-2.3.7.8-.5 1.5-1.3 1.8-2.3-.8.5-1.7.8-2.6 1a4.1 4.1 0 0 0-7 3.7A11.6 11.6 0 0 1 3.4 4.6a4.1 4.1 0 0 0 1.3 5.5c-.7 0-1.3-.2-1.9-.5v.1c0 2 1.4 3.6 3.3 4a4.1 4.1 0 0 1-1.9.1 4.1 4.1 0 0 0 3.8 2.8A8.2 8.2 0 0 1 2 18.4a11.6 11.6 0 0 0 6.3 1.8c7.5 0 11.7-6.3 11.7-11.7v-.5c.8-.6 1.5-1.3 2-2.1Z"/>',
        'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5.5"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.3" cy="6.7" r="1" fill="currentColor"/>',
        'mail' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m3 6 9 7 9-7"/>',
        'phone' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2Z"/>',
        'map-pin' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'sun' => '<circle cx="12" cy="12" r="4.5"/><path d="M12 2v2.5M12 19.5V22M4.2 4.2l1.8 1.8M18 18l1.8 1.8M2 12h2.5M19.5 12H22M4.2 19.8 6 18M18 6l1.8-1.8" stroke-linecap="round"/>',
        'moon' => '<path d="M20.5 14.5A8.5 8.5 0 1 1 9.5 3.5a7 7 0 0 0 11 11Z"/>',
        'menu' => '<path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round"/>',
        'close' => '<path d="M18 6 6 18M6 6l12 12" stroke-linecap="round"/>',
        'download' => '<path d="M12 3v13m0 0-5-5m5 5 5-5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 20h16" stroke-linecap="round"/>',
        'external-link' => '<path d="M14 4h6v6M20 4 10 14M6 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-1" stroke-linecap="round" stroke-linejoin="round"/>',
        'send' => '<path d="M22 2 11 13M22 2l-7 20-4-9-9-4Z" stroke-linecap="round" stroke-linejoin="round"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35" stroke-linecap="round"/>',
        'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
        'graduation-cap' => '<path d="m22 10-10-5L2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"/>',
        'award' => '<circle cx="12" cy="8" r="6"/><path d="m9 14-1.5 7L12 19l4.5 2L15 14"/>',
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>',
        'arrow-left' => '<path d="M19 12H5M11 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>',
        'chevron-left' => '<path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>',
        'arrow-up' => '<path d="M12 19V5M6 11l6-6 6 6" stroke-linecap="round" stroke-linejoin="round"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18" stroke-linecap="round"/>',
        'eye' => '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/>',
        'check' => '<path d="M20 6 9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>',
        'tag' => '<path d="M20.6 12.3 12.3 20.6a2 2 0 0 1-2.83 0L3 14.13a2 2 0 0 1 0-2.83L11.3 3H19a2 2 0 0 1 2 2v7.3Z"/><circle cx="15.5" cy="8.5" r="1.3" fill="currentColor"/>',
        'trash' => '<path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-linecap="round"/>',
        'edit' => '<path d="M12 20h9" stroke-linecap="round"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" stroke-linecap="round" stroke-linejoin="round"/>',
        'plus' => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
        'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke-linecap="round"/><path d="m16 17 5-5-5-5M21 12H9" stroke-linecap="round" stroke-linejoin="round"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.5-7 8-7s8 3 8 7"/>',
        'sparkle' => '<path d="M12 2 14 9 21 12 14 15 12 22 10 15 3 12 10 9Z"/>',
        'camera' => '<path d="M4 8h3l1.6-2.4A2 2 0 0 1 10.3 4.6h3.4a2 2 0 0 1 1.7 1L17 8h3a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2Z"/><circle cx="12" cy="13.5" r="3.5"/>',
        'clock' => '<circle cx="12" cy="12" r="9.5"/><path d="M12 7v5l3.5 2" stroke-linecap="round" stroke-linejoin="round"/>',
    ];
    $paths = $icons[$name] ?? $icons['code'];
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" xmlns="http://www.w3.org/2000/svg">' . $paths . '</svg>';
}

/* ---------------------------------------------------------------
 * File uploads
 * ------------------------------------------------------------- */

/**
 * Handles a single uploaded image/PDF file. Returns the relative path
 * (e.g. "uploads/projects/xxx.jpg") on success, or null if no file was
 * uploaded. Throws RuntimeException on validation failure.
 */
function handle_upload(string $fieldName, string $subDir, array $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']): ?string
{
    if (empty($_FILES[$fieldName]['name']) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed. Please try again.');
    }
    if ($_FILES[$fieldName]['size'] > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('File is too large (max 5MB).');
    }

    $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        throw new RuntimeException('Invalid file type. Allowed: ' . implode(', ', $allowedExt));
    }

    $destDir = UPLOADS_DIR . '/' . $subDir;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $filename = bin2hex(random_bytes(8)) . '.' . $ext;
    $destPath = $destDir . '/' . $filename;

    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destPath)) {
        throw new RuntimeException('Could not save uploaded file.');
    }

    return UPLOADS_URL . '/' . $subDir . '/' . $filename;
}

/* ---------------------------------------------------------------
 * Flash messages
 * ------------------------------------------------------------- */

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
