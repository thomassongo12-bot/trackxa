<?php
class AdminContactController extends Controller {
    public function index(array $params = []): void {
        $this->requireAdmin();
        $page  = max(1, (int)$this->get('page', 1));
        $total = Database::getInstance()->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
        $offset= ($page - 1) * 20;
        $msgs  = Database::getInstance()->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 20 OFFSET {$offset}")->fetchAll();
        $this->view('admin.contacts.index', [
            'title'   => 'Contact Messages – Admin',
            'msgs'    => $msgs,
            'total'   => $total,
            'page'    => $page,
            'pages'   => ceil($total / 20),
        ], 'admin');
    }

    public function view(array $params = []): void {
        $this->requireAdmin();
        $id  = (int)$params['id'];
        $msg = Database::getInstance()->prepare("SELECT * FROM contact_messages WHERE id=?")->execute([$id]);
        $stmt= Database::getInstance()->prepare("SELECT * FROM contact_messages WHERE id=?");
        $stmt->execute([$id]);
        $msg = $stmt->fetch();
        if (!$msg) $this->notFound();
        if (!$msg['is_read']) {
            Database::getInstance()->prepare("UPDATE contact_messages SET is_read=1 WHERE id=?")->execute([$id]);
        }
        $this->view('admin.contacts.view', ['title' => 'Message – Admin', 'msg' => $msg], 'admin');
    }

    public function reply(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id    = (int)$params['id'];
        $stmt  = Database::getInstance()->prepare("SELECT * FROM contact_messages WHERE id=?");
        $stmt->execute([$id]);
        $msg   = $stmt->fetch();
        if (!$msg) $this->notFound();
        $reply = $_POST['reply'] ?? '';
        $siteName = Settings::get('site_name', 'TrackXa');
        $body  = "<p>Hello {$msg['name']},</p>" . nl2br(Security::e($reply)) . "<p>— {$siteName} Support</p>";
        EmailService::send($msg['email'], 'Re: ' . $msg['subject'], $body);
        Database::getInstance()->prepare("UPDATE contact_messages SET reply=?,replied_at=NOW() WHERE id=?")->execute([$reply, $id]);
        Session::getInstance()->flash('success', 'Reply sent.');
        $this->redirect(BASE_URL . '/admin/contacts/' . $id);
    }
}
