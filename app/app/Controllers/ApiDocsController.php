<?php
class ApiDocsController extends Controller {
    public function index(array $params = []): never {
        require ROOT_PATH . '/api/docs/index.php';
        exit;
    }
}
