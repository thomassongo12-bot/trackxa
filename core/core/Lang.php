<?php
/**
 * TrackXa - Language / i18n
 */
class Lang {
    private static ?Lang $instance = null;
    private array $strings = [];
    private string $locale  = 'en';

    private function __construct(string $locale) {
        $this->locale = $locale;
        $this->load($locale);
    }

    public static function getInstance(): Lang {
        if (self::$instance === null) {
            $locale = $_SESSION['lang'] ?? DEFAULT_LANG;
            if (!in_array($locale, SUPPORTED_LANGS)) $locale = DEFAULT_LANG;
            self::$instance = new self($locale);
        }
        return self::$instance;
    }

    private function load(string $locale): void {
        $file = LANG_PATH . '/' . $locale . '/app.php';
        if (file_exists($file)) {
            $this->strings = require $file;
        }
        // Fallback to English
        if ($locale !== 'en') {
            $en = LANG_PATH . '/en/app.php';
            if (file_exists($en)) {
                $this->strings = array_merge(require $en, $this->strings);
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
    public function isRtl(): bool { return in_array($this->locale, ['ar']); }

    public static function set(string $locale): void {
        $_SESSION['lang'] = in_array($locale, SUPPORTED_LANGS) ? $locale : DEFAULT_LANG;
        self::$instance = null; // reset
    }
}
