<?php
class AdminApiKeyController extends Controller {
    private ApiKeyModel $model;

    public function __construct() { $this->model = new ApiKeyModel(); }

    public function index(array $params = []): void {
        $this->requireAdmin();
        $db   = Database::getInstance();
        $keys = $db->query("
            SELECT k.*, w.name AS website_name, w.domain
            FROM api_keys k LEFT JOIN websites w ON k.website_id = w.id
            ORDER BY k.created_at DESC
        ")->fetchAll();
        $this->view('admin.api_keys.index', ['title' => 'API Keys – Admin', 'keys' => $keys], 'admin');
    }

    public function generate(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $websiteId = (int)$this->post('website_id');
        if (!$websiteId) {
            Session::getInstance()->flash('error', 'Select a website first.');
            $this->redirect(BASE_URL . '/admin/api-keys');
        }
        $pair = $this->model->generateKeyPair($websiteId);
        Session::getInstance()->flash('new_key', json_encode($pair));
        Session::getInstance()->flash('success', 'API key generated. Save your secret — it will only be shown once!');
        $this->redirect(BASE_URL . '/admin/api-keys');
    }

    public function revoke(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = (int)$params['id'];
        $this->model->update($id, ['status' => 'revoked']);
        Session::getInstance()->flash('success', 'API key revoked.');
        $this->redirect(BASE_URL . '/admin/api-keys');
    }

    public function logs(array $params = []): void {
        $this->requireAdmin();
        $page = max(1, (int)$this->get('page', 1));
        $db   = Database::getInstance();
        $total= $db->query("SELECT COUNT(*) FROM api_logs")->fetchColumn();
        $offset = ($page - 1) * 25;
        $logs = $db->query("
            SELECT l.*, k.api_key, w.name AS website_name
            FROM api_logs l
            LEFT JOIN api_keys k ON l.api_key_id = k.id
            LEFT JOIN websites w ON k.website_id = w.id
            ORDER BY l.created_at DESC LIMIT 25 OFFSET {$offset}
        ")->fetchAll();
        $this->view('admin.api_keys.logs', [
            'title'  => 'API Logs – Admin',
            'logs'   => $logs,
            'total'  => $total,
            'page'   => $page,
            'pages'  => ceil($total / 25),
        ], 'admin');
    }
}
