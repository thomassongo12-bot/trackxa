<?php
class AdminDashboardController extends Controller {
    public function index(array $params = []): void {
        $this->requireAdmin();
        $shipment = new ShipmentModel();
        $stats    = $shipment->getStats();
        $latest   = $shipment->getLatest(10);

        $db = Database::getInstance();

        // API usage this month
        $apiStats = $db->query("SELECT COUNT(*) AS total, SUM(response_code=200) AS success FROM api_logs WHERE MONTH(created_at)=MONTH(NOW())")->fetch();

        // Visitor stats (last 7 days)
        $visitorStats = $db->query("
            SELECT DATE(created_at) AS day, COUNT(*) AS visits
            FROM visitor_stats
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at) ORDER BY day ASC
        ")->fetchAll();

        // Recent activity
        $recentLogs = $db->query("
            SELECT l.*, a.name AS admin_name
            FROM admin_logs l LEFT JOIN admins a ON l.admin_id = a.id
            ORDER BY l.created_at DESC LIMIT 10
        ")->fetchAll();

        // Shipments by status
        $byStatus = $db->query("
            SELECT status, COUNT(*) AS cnt FROM shipments WHERE is_archived=0 GROUP BY status
        ")->fetchAll();

        // Countries count
        $countryCount = $db->query("
            SELECT COUNT(DISTINCT destination_country_id) AS cnt FROM shipments WHERE destination_country_id IS NOT NULL
        ")->fetchColumn();

        $this->view('admin.dashboard', [
            'title'        => 'Dashboard – ' . Settings::get('site_name', 'TrackXa'),
            'stats'        => $stats,
            'latest'       => $latest,
            'apiStats'     => $apiStats,
            'visitorStats' => $visitorStats,
            'recentLogs'   => $recentLogs,
            'byStatus'     => $byStatus,
            'countryCount' => $countryCount,
        ], 'admin');
    }
}
