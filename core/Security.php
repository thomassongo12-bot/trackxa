<?php
/**
 * TrackXa - Security Helpers
 */
class Security {

    public static function csrfToken(): string {
        if (empty($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }

    public static function verifyCsrf(string $token): bool {
        return isset($_SESSION[CSRF_TOKEN_NAME])
            && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }

    public static function sanitize(mixed $value): mixed {
        if (is_array($value)) {
            return array_map([self::class, 'sanitize'], $value);
        }
        if (is_string($value)) {
            return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
        }
        return $value;
    }

    public static function sanitizeHtml(string $value): string {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public static function generateToken(int $bytes = 32): string {
        return bin2hex(random_bytes($bytes));
    }

    public static function rateLimit(string $key, int $maxRequests = 60, int $window = 60): bool {
        $sessionKey = 'rl_' . md5($key);
        $now = time();
        if (!isset($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey] = ['count' => 0, 'start' => $now];
        }
        $data = &$_SESSION[$sessionKey];
        if ($now - $data['start'] > $window) {
            $data = ['count' => 0, 'start' => $now];
        }
        $data['count']++;
        return $data['count'] <= $maxRequests;
    }

    public static function ip(): string {
        $keys = ['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','HTTP_X_REAL_IP','REMOTE_ADDR'];
        foreach ($keys as $k) {
            if (!empty($_SERVER[$k])) {
                $ip = explode(',', $_SERVER[$k])[0];
                if (filter_var(trim($ip), FILTER_VALIDATE_IP)) return trim($ip);
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public static function validateEmail(string $email): bool {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validateUrl(string $url): bool {
        return (bool)filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function e(mixed $v): string {
        return htmlspecialchars((string)$v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
