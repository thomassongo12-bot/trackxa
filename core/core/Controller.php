<?php
/**
 * TrackXa - Base Controller
 */
abstract class Controller {
    protected array $data = [];

    protected function view(string $view, array $data = [], string $layout = 'main'): void {
        $data['lang']     = Lang::getInstance();
        $data['settings'] = Settings::getAll();
        $data['session']  = Session::getInstance();
        $data['csrf']     = Security::csrfToken();
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

    protected function post(string $key, mixed $default = null): mixed {
        return Security::sanitize($_POST[$key] ?? $default);
    }

    protected function get(string $key, mixed $default = null): mixed {
        return Security::sanitize($_GET[$key] ?? $default);
    }

    protected function validateCsrf(): void {
        if (!Security::verifyCsrf($this->post(CSRF_TOKEN_NAME, ''))) {
            http_response_code(403);
            die('Invalid security token. Please refresh the page and try again.');
        }
    }

    protected function paginate(int $page, int $perPage = 20): array {
        return ['page' => max(1, (int)$page), 'perPage' => $perPage];
    }
}
