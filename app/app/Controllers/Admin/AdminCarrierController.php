<?php
class AdminCarrierController extends Controller {
    public function index(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        // Handle POST actions inline
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $this->post('action', '');
            $db = Database::getInstance();
            if ($action === 'add') {
                $db->prepare("INSERT INTO carriers (name,code,website,tracking_url,status) VALUES (?,?,?,?,1)")
                   ->execute([$this->post('name'), $this->post('code'), $this->post('website'), $this->post('tracking_url')]);
            } elseif ($action === 'edit') {
                $db->prepare("UPDATE carriers SET name=?,code=?,website=?,tracking_url=?,status=? WHERE id=?")
                   ->execute([$this->post('name'), $this->post('code'), $this->post('website'), $this->post('tracking_url'), $this->post('status',1), $this->post('id')]);
            } elseif ($action === 'delete') {
                $db->prepare("DELETE FROM carriers WHERE id=?")->execute([$this->post('id')]);
            }
            Session::getInstance()->flash('success', 'Changes saved.');
            $this->redirect(BASE_URL . '/admin/carriers');
        }
        $carriers = Database::getInstance()->query("SELECT * FROM carriers ORDER BY name")->fetchAll();
        $this->view('admin.carriers', ['title' => 'Carriers – Admin', 'carriers' => $carriers], 'admin');
    }
}
