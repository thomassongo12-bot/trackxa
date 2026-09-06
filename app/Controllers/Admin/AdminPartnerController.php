<?php
class AdminPartnerController extends Controller {
    public function index(array $params = []): void {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $action = $this->post('action');
            $db = Database::getInstance();
            if ($action === 'add') {
                $logo = '';
                if (!empty($_FILES['logo']['name'])) $logo = UploadService::image($_FILES['logo'], 'partners') ?: '';
                $db->prepare("INSERT INTO partners (name,logo,website,sort_order) VALUES (?,?,?,?)")
                   ->execute([$this->post('name'), $logo, $this->post('website'), (int)$this->post('sort_order',0)]);
            } elseif ($action === 'delete') {
                $db->prepare("DELETE FROM partners WHERE id=?")->execute([(int)$this->post('id')]);
            }
            Session::getInstance()->flash('success', 'Partners updated.');
            $this->redirect(BASE_URL . '/admin/partners');
        }
        $partners = Database::getInstance()->query("SELECT * FROM partners ORDER BY sort_order")->fetchAll();
        $this->view('admin.partners', ['title' => 'Partners – Admin', 'partners' => $partners], 'admin');
    }
}
