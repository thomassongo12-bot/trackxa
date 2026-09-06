<?php
/**
 * TrackXa - Base Controller
 */
abstract class Controller {
    protected array $data = [];

    protected function view(string $view, array $data = [], string $layout = 'main'): void {
        $data['lang']      = Lang::getInstance();
        $data['adminLang'] = class_exists('AdminLang') ? AdminLang::getInstance() : null;
        $data['settings']  = Settings::getAll();
        $data['session']   = Session::getInstance();
        $data['csrf']      = Security::csrfToken();
        $this->data       = array_merge($this->data, $data);
        extract($this->data);

        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            throw new RuntimeException("View not found: {$view}");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout) {
            $layoutFile = APP_PATH . '/Views/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
                return;
            }
        }
        echo $content;
    }

    protected function json(array $data, int $code = 200): never {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $url, int $code = 302): never {
        header("Location: {$url}", true, $code);
        exit;
    }

    protected function notFound(): never {
        http_response_code(404);
        $this->view('errors.404', [], 'main');
        exit;
    }

    protected function requireAdmin(): void {
        if (!Session::getInstance()->get('admin_id')) {
            $this->redirect(BASE_URL . '/admin/login');
        }
    }

    protected function input(string $key, mixed $default = null): mixed {
        return Security::sanitize($_POST[$key] ?? $_GET[$key] ?? $default);
    }

    protected function postParam(string $key, mixed $default = null): mixed {
        return Security::sanitize($_POST[$key] ?? $default);
    }

    // Alias kept for backward compat
    protected function post(string $key, mixed $default = null): mixed {
        return $this->postParam($key, $default);
    }

    protected function getParam(string $key, mixed $default = null): mixed {
        return Security::sanitize($_GET[$key] ?? $default);
    }

    // Alias kept for backward compat
    protected function get(string $key, mixed $default = null): mixed {
        return $this->getParam($key, $default);
    }

    protected function validateCsrf(): void {
        $submitted = $this->post(CSRF_TOKEN_NAME, '');
        $stored    = $_SESSION[CSRF_TOKEN_NAME] ?? '';
        if (empty($submitted) || empty($stored) || !hash_equals($stored, $submitted)) {
            // Regenerate token for next attempt
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
            http_response_code(403);
            if (ENV === 'development') {
                die('CSRF Error: Token mismatch. Submitted=['.substr($submitted,0,16).'] Stored=['.substr($stored,0,16).'] Session=['.session_id().']');
            }
            die('Invalid security token. Please <a href="javascript:history.back()">go back</a> and try again.');
        }
    }

    protected function paginate(int $page, int $perPage = 20): array {
        return ['page' => max(1, (int)$page), 'perPage' => $perPage];
    }
}
