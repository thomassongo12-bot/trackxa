<?php
class ApiDocsController extends Controller {
    public function index(array $params = []): never {
        $this->requireAdmin();
        require ROOT_PATH . '/api/docs/index.php';
        exit;
    }
}
