<?php
class AdminLogController extends Controller {
    public function index(array $params = []): void {
        $this->requireAdmin();
        $page  = max(1, (int)$this->get('page', 1));
        $total = Database::getInstance()->query("SELECT COUNT(*) FROM admin_logs")->fetchColumn();
        $offset= ($page - 1) * 30;
        $logs  = Database::getInstance()->query("
            SELECT l.*, a.name AS admin_name
            FROM admin_logs l LEFT JOIN admins a ON l.admin_id = a.id
            ORDER BY l.created_at DESC LIMIT 30 OFFSET {$offset}
        ")->fetchAll();
        $this->view('admin.logs', [
            'title' => 'Activity Logs – Admin',
            'logs'  => $logs, 'total' => $total,
            'page'  => $page, 'pages' => ceil($total / 30),
        ], 'admin');
    }
}
