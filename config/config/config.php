<?php
/**
 * TrackXa - Main Configuration
 */

// ── Environment ──────────────────────────────────────────────
define('ENV', 'development'); // production | development

// ── Paths ────────────────────────────────────────────────────
define('ROOT_PATH',   dirname(__DIR__));
define('APP_PATH',    ROOT_PATH . '/app');
define('CORE_PATH',   ROOT_PATH . '/core');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH',ROOT_PATH . '/storage');
define('LANG_PATH',   ROOT_PATH . '/lang');

// ── Base URL (auto-detected) ──────────────────────────────────
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir= dirname($_SERVER['SCRIPT_NAME'] ?? '/');

// PHP built-in server serves from /public directly → ASSETS_URL = BASE_URL
// Apache/Nginx with document root at /public → same
// Apache with document root at project root → ASSETS_URL = BASE_URL/public
$isBuiltIn = php_sapi_name() === 'cli-server';
$base      = rtrim($protocol . '://' . $host . ($scriptDir === '/' || $isBuiltIn ? '' : $scriptDir), '/');
define('BASE_URL',   $base);
define('ASSETS_URL', $isBuiltIn ? BASE_URL : BASE_URL . '/public');

// ── Database ─────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'trackxa');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// ── Security ─────────────────────────────────────────────────
define('SECRET_KEY',     'txA!k3y$2025#secR3t_ch4ng3_me');
define('CSRF_TOKEN_NAME','_csrf_token');
define('SESSION_NAME',   'trackxa_sess');

// ── Application ───────────────────────────────────────────────
define('DEFAULT_LANG',    'en');
define('SUPPORTED_LANGS', ['en','fr','es','de','it','pt','ar']);
define('TIMEZONE',        'UTC');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMG_TYPES', ['image/jpeg','image/png','image/webp','image/gif']);

// ── API ───────────────────────────────────────────────────────
define('API_VERSION',    'v1');
define('API_RATE_LIMIT', 1000); // requests per day per key

// ── Timezone ─────────────────────────────────────────────────
date_default_timezone_set(TIMEZONE);

// ── Error display ─────────────────────────────────────────────
if (ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
