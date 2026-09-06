<?php
/**
 * TrackXa - Database (PDO Singleton)
 */
class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                if (ENV === 'development') {
                    die('Database connection failed: ' . $e->getMessage());
                }
                die('Database connection failed. Please try again later.');
            }
        }
        return self::$instance;
    }

    // Prevent cloning
    private function __clone() {}
    private function __construct() {}
}
