<?php
/**
 * TrackXa - PSR-4 Style Autoloader
 */
class Autoloader {
    private static array $paths = [];

    public static function register(): void {
        self::$paths = [
            CORE_PATH,
            APP_PATH . '/Models',
            APP_PATH . '/Controllers',
            APP_PATH . '/Controllers/Admin',
            APP_PATH . '/Controllers/Api',
            APP_PATH . '/Services',
            APP_PATH . '/Middleware',
            APP_PATH . '/Helpers',
        ];
        spl_autoload_register([self::class, 'load']);
    }

    private static function load(string $class): void {
        $file = $class . '.php';
        foreach (self::$paths as $path) {
            $full = $path . DIRECTORY_SEPARATOR . $file;
            if (file_exists($full)) {
                require_once $full;
                return;
            }
        }
    }
}
