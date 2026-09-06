<?php
class AdminFaqController extends Controller {
    public function index(array $params = []): void {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $db = Database::getInstance();
            $action = $this->post('action', '');
            if ($action === 'add') {
                $db->prepare("INSERT INTO faqs (question,answer,category,sort_order,lang) VALUES (?,?,?,?,?)")
                   ->execute([$this->post('question'),$this->post('answer'),$this->post('category','general'),(int)$this->post('sort_order',0),$this->post('lang','en')]);
            } elseif ($action === 'edit') {
                $db->prepare("UPDATE faqs SET question=?,answer=?,category=?,sort_order=?,lang=?,status=? WHERE id=?")
                   ->execute([$this->post('question'),$this->post('answer'),$this->post('category','general'),(int)$this->post('sort_order',0),$this->post('lang','en'),(int)$this->post('status',1),(int)$this->post('id')]);
            } elseif ($action === 'delete') {
                $db->prepare("DELETE FROM faqs WHERE id=?")->execute([(int)$this->post('id')]);
            }
            Session::getInstance()->flash('success', 'FAQ updated.');
            $this->redirect(BASE_URL . '/admin/faq');
        }
        $faqs = Database::getInstance()->query("SELECT * FROM faqs ORDER BY lang, sort_order")->fetchAll();
        $this->view('admin.faq', ['title' => 'FAQ – Admin', 'faqs' => $faqs], 'admin');
    }
}
