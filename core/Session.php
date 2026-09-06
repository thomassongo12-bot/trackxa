<?php
/**
 * TrackXa - Session Manager
 */
class Session {
    private static ?Session $instance = null;

    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            // Detect correct cookie path
            $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
            $cookiePath = ($scriptDir === '/' || $scriptDir === '\\') ? '/' : $scriptDir . '/';
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => $cookiePath,
                'domain'   => '',
                'secure'   => !empty($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function getInstance(): Session {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set(string $key, mixed $value): void  { $_SESSION[$key] = $value; }
    public function get(string $key, mixed $default = null): mixed { return $_SESSION[$key] ?? $default; }
    public function has(string $key): bool  { return isset($_SESSION[$key]); }
    public function remove(string $key): void { unset($_SESSION[$key]); }

    public function flash(string $key, mixed $value): void {
        $_SESSION['_flash'][$key] = $value;
    }

    public function getFlash(string $key, mixed $default = null): mixed {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    public function destroy(): void {
        session_destroy();
        $_SESSION = [];
    }

    public function regenerate(): void {
        session_regenerate_id(true);
    }

    public function isAdmin(): bool {
        return $this->has('admin_id') && $this->get('admin_role') !== null;
    }
}
