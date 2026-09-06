<?php
class AdminSettingsController extends Controller {
    public function index(array $params = []): void {
        $this->requireAdmin();
        $db = Database::getInstance();
        $rows = $db->query("SELECT * FROM settings ORDER BY `group`, `key`")->fetchAll();
        $grouped = [];
        foreach ($rows as $row) { $grouped[$row['group']][$row['key']] = $row['value']; }
        $this->view('admin.settings', ['title' => 'Settings – Admin', 'grouped' => $grouped], 'admin');
    }

    public function save(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $skip = ['_csrf_token'];
        foreach ($_POST as $key => $value) {
            if (in_array($key, $skip)) continue;
            Settings::set(Security::sanitize($key), is_array($value) ? implode(',', $value) : Security::sanitize($value));
        }
        // Logo upload
        if (!empty($_FILES['site_logo']['name'])) {
            $path = UploadService::image($_FILES['site_logo'], 'uploads');
            if ($path) Settings::set('site_logo', $path);
        }
        if (!empty($_FILES['site_favicon']['name'])) {
            $path = UploadService::image($_FILES['site_favicon'], 'uploads');
            if ($path) Settings::set('site_favicon', $path);
        }
        (new AdminModel())->logActivity(Session::getInstance()->get('admin_id'), 'UPDATE_SETTINGS');
        Session::getInstance()->flash('success', 'Settings saved successfully.');
        $this->redirect(BASE_URL . '/admin/settings');
    }
}
