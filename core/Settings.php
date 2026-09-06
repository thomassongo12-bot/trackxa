<?php
/**
 * TrackXa - Application Settings (cached from DB)
 */
class Settings {
    private static array $cache = [];
    private static bool  $loaded = false;

    public static function getAll(): array {
        if (!self::$loaded) {
            self::load();
        }
        return self::$cache;
    }

    public static function get(string $key, mixed $default = null): mixed {
        if (!self::$loaded) self::load();
        return self::$cache[$key] ?? $default;
    }

    public static function set(string $key, string $value): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=?");
        $stmt->execute([$key, $value, $value]);
        self::$cache[$key] = $value;
    }

    private static function load(): void {
        try {
            $db = Database::getInstance();
            $rows = $db->query("SELECT `key`,`value` FROM settings")->fetchAll();
            foreach ($rows as $row) {
                self::$cache[$row['key']] = $row['value'];
            }
        } catch (Exception $e) {
            // DB not ready yet
        }
        self::$loaded = true;
    }
}
