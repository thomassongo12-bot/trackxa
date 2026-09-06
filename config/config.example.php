<?php
define('ENV', 'development');
define('ROOT_PATH',   dirname(__DIR__));
define('APP_PATH',    ROOT_PATH . '/app');
define('CORE_PATH',   ROOT_PATH . '/core');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH',ROOT_PATH . '/storage');
define('LANG_PATH',   ROOT_PATH . '/lang');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Detect base URL dynamically:
// - If accessed via trackxa.local or php -S (no /trackxa/public subfolder), use root
// - If accessed via localhost/trackxa/public subfolder, include the path
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
if (strpos($scriptName, '/trackxa/public') !== false) {
    $basePath = '/trackxa/public';
} else {
    $basePath = '';
}
define('BASE_URL',   $protocol . '://' . $host . $basePath);
define('ASSETS_URL', $protocol . '://' . $host . $basePath);

define('DB_HOST',    'localhost');
define('DB_NAME',    'your_db_name');
define('DB_USER',    'your_db_user');
define('DB_PASS',    'your_db_password');
define('DB_CHARSET', 'utf8mb4');

define('SECRET_KEY',     'txA!k3y$2025#secR3t_ch4ng3_me');
define('CSRF_TOKEN_NAME','_csrf_token');
define('SESSION_NAME',   'trackxa_sess');

define('DEFAULT_LANG',    'en');
define('SUPPORTED_LANGS', ['en','fr','es','de','it','pt','ar']);
define('TIMEZONE',        'UTC');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024);
define('ALLOWED_IMG_TYPES', ['image/jpeg','image/png','image/webp','image/gif']);

define('API_VERSION',    'v1');
define('API_RATE_LIMIT', 1000);

date_default_timezone_set(TIMEZONE);
ini_set('display_errors', 1);
error_reporting(E_ALL);

