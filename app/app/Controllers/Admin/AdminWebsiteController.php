<?php
class AdminWebsiteController extends Controller {
    private WebsiteModel $model;

    public function __construct() { $this->model = new WebsiteModel(); }

    public function index(array $params = []): void {
        $this->requireAdmin();
        $db       = Database::getInstance();
        $websites = $db->query("
            SELECT w.*, COUNT(k.id) AS key_count
            FROM websites w LEFT JOIN api_keys k ON k.website_id = w.id AND k.status='active'
            GROUP BY w.id ORDER BY w.name
        ")->fetchAll();
        $this->view('admin.websites.index', ['title' => 'Websites – Admin', 'websites' => $websites], 'admin');
    }

    public function create(array $params = []): void {
        $this->requireAdmin();
        $this->view('admin.websites.form', ['title' => 'Add Website – Admin', 'website' => null], 'admin');
    }

    public function store(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = $this->model->insert([
            'name'          => $this->post('name'),
            'domain'        => $this->post('domain'),
            'contact_email' => $this->post('contact_email'),
            'status'        => 'active',
        ]);
        Session::getInstance()->flash('success', 'Website added.');
        $this->redirect(BASE_URL . '/admin/websites/' . $id . '/edit');
    }

    public function edit(array $params = []): void {
        $this->requireAdmin();
        $website = $this->model->getWithKeys((int)$params['id']);
        if (!$website) $this->notFound();
        $db  = Database::getInstance();
        $stmt= $db->prepare("SELECT * FROM api_keys WHERE website_id=? ORDER BY created_at DESC");
        $stmt->execute([$website['id']]);
        $keys = $stmt->fetchAll();
        $stmt2= $db->prepare("SELECT * FROM webhooks WHERE website_id=? ORDER BY id DESC");
        $stmt2->execute([$website['id']]);
        $hooks = $stmt2->fetchAll();
        $this->view('admin.websites.form', [
            'title'   => 'Edit Website – Admin',
            'website' => $website,
            'keys'    => $keys,
            'hooks'   => $hooks,
        ], 'admin');
    }

    public function update(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = (int)$params['id'];
        $this->model->update($id, [
            'name'          => $this->post('name'),
            'domain'        => $this->post('domain'),
            'contact_email' => $this->post('contact_email'),
            'status'        => $this->post('status', 'active'),
        ]);
        Session::getInstance()->flash('success', 'Website updated.');
        $this->redirect(BASE_URL . '/admin/websites/' . $id . '/edit');
    }
}
