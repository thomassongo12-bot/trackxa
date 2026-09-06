<?php
class AdminCarrierController extends Controller {
    public function index(array $params = []): void {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $action = $this->post('action', '');
            $db     = Database::getInstance();

            if ($action === 'add') {
                $logo = null;
                if (!empty($_FILES['logo']['name'])) {
                    $logo = UploadService::image($_FILES['logo'], 'carriers');
                }
                $db->prepare("INSERT INTO carriers (name,code,website,tracking_url,logo,status) VALUES (?,?,?,?,?,1)")
                   ->execute([
                       $this->post('name'),
                       strtoupper(trim($this->post('code'))),
                       $this->post('website'),
                       $this->post('tracking_url'),
                       $logo,
                   ]);

            } elseif ($action === 'edit') {
                $id  = (int)$this->post('id');
                // Fetch existing logo for possible deletion
                $old = $db->prepare("SELECT logo FROM carriers WHERE id=?")->execute([$id]) ? null : null;
                $row = $db->prepare("SELECT logo FROM carriers WHERE id=?");
                $row->execute([$id]);
                $existing = $row->fetchColumn();

                $logo = $existing; // keep old by default
                if (!empty($_FILES['logo']['name'])) {
                    $uploaded = UploadService::image($_FILES['logo'], 'carriers');
                    if ($uploaded) {
                        // delete old logo file
                        if ($existing) UploadService::delete($existing);
                        $logo = $uploaded;
                    }
                }
                // Remove logo if checkbox checked
                if ($this->post('remove_logo') === '1' && $existing) {
                    UploadService::delete($existing);
                    $logo = null;
                }

                $db->prepare("UPDATE carriers SET name=?,code=?,website=?,tracking_url=?,logo=?,status=? WHERE id=?")
                   ->execute([
                       $this->post('name'),
                       strtoupper(trim($this->post('code'))),
                       $this->post('website'),
                       $this->post('tracking_url'),
                       $logo,
                       (int)$this->post('status', 1),
                       $id,
                   ]);

            } elseif ($action === 'delete') {
                $id  = (int)$this->post('id');
                $row = $db->prepare("SELECT logo FROM carriers WHERE id=?");
                $row->execute([$id]);
                $logo = $row->fetchColumn();
                if ($logo) UploadService::delete($logo);
                $db->prepare("DELETE FROM carriers WHERE id=?")->execute([$id]);
            }

            Session::getInstance()->flash('success', 'Changes saved.');
            $this->redirect(BASE_URL . '/admin/carriers');
        }

        $carriers = Database::getInstance()->query("SELECT * FROM carriers ORDER BY name")->fetchAll();
        $this->view('admin.carriers', ['title' => 'Carriers – Admin', 'carriers' => $carriers], 'admin');
    }
}
