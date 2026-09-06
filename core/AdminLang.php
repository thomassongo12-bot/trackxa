<?php
/**
 * TrackXa – Admin Language Helper
 * Loads admin-specific translations (separate from public translations)
 */
class AdminLang {
    private static ?AdminLang $instance = null;
    private array  $strings  = [];
    private string $locale   = 'en';

    private function __construct(string $locale) {
        $this->locale = in_array($locale, ['en','fr']) ? $locale : 'en';
        $this->load();
    }

    public static function getInstance(): AdminLang {
        if (self::$instance === null) {
            $locale = $_SESSION['admin_lang'] ?? $_SESSION['lang'] ?? 'en';
            // Admin only supports EN and FR
            $locale = in_array($locale, ['en','fr']) ? $locale : 'en';
            self::$instance = new self($locale);
        }
        return self::$instance;
    }

    private function load(): void {
        // Load English base
        $en = LANG_PATH . '/en/admin.php';
        if (file_exists($en)) {
            $this->strings = require $en;
        }
        // Override with locale if not English
        if ($this->locale !== 'en') {
            $file = LANG_PATH . '/' . $this->locale . '/admin.php';
            if (file_exists($file)) {
                $this->strings = array_merge($this->strings, require $file);
            }
        }
    }

    public function get(string $key, array $replace = []): string {
        $text = $this->strings[$key] ?? $key;
        foreach ($replace as $k => $v) {
            $text = str_replace(':' . $k, $v, $text);
        }
        return $text;
    }

    public function getLocale(): string { return $this->locale; }

    public static function set(string $locale): void {
        $locale = in_array($locale, ['en','fr']) ? $locale : 'en';
        $_SESSION['admin_lang'] = $locale;
        self::$instance = null;
    }
}
