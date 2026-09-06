<?php
class AdminProfileController extends Controller {
    private AdminModel $admin;
    public function __construct() { $this->admin = new AdminModel(); }

    public function index(array $params = []): void {
        $this->requireAdmin();
        $admin = $this->admin->find(Session::getInstance()->get('admin_id'));
        $this->view('admin.profile', ['title' => 'My Profile – Admin', 'admin' => $admin], 'admin');
    }

    public function update(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id   = Session::getInstance()->get('admin_id');
        $data = ['name' => $this->post('name')];

        if (!empty($_FILES['avatar']['name'])) {
            $path = UploadService::image($_FILES['avatar'], 'uploads');
            if ($path) $data['avatar'] = $path;
        }

        $newPass = $this->post('new_password', '');
        if ($newPass) {
            if (strlen($newPass) < 8) {
                Session::getInstance()->flash('error', 'Password must be at least 8 characters.');
                $this->redirect(BASE_URL . '/admin/profile');
            }
            $current = $this->admin->find($id);
            if (!Security::verifyPassword($this->post('current_password', ''), $current['password'])) {
                Session::getInstance()->flash('error', 'Current password is incorrect.');
                $this->redirect(BASE_URL . '/admin/profile');
            }
            $data['password'] = Security::hashPassword($newPass);
        }

        $this->admin->update($id, $data);
        Session::getInstance()->set('admin_name', $data['name']);
        Session::getInstance()->flash('success', 'Profile updated.');
        $this->redirect(BASE_URL . '/admin/profile');
    }
}
