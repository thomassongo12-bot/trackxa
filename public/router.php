<?php
/**
 * TrackXa - PHP Built-in Server Router
 * Used only with: php -S localhost:8080 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve existing static files directly (CSS, JS, images, fonts)
$staticFile = __DIR__ . $uri;
if ($uri !== '/' && file_exists($staticFile) && !is_dir($staticFile)) {
    // Set proper MIME types
    $ext = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
    $mimes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'webp' => 'image/webp',
        'ico'  => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'pdf'  => 'application/pdf',
    ];
    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
    }
    return false; // Serve the file directly
}

// Route all other requests through index.php
require __DIR__ . '/index.php';
