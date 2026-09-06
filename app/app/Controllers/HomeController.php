<?php
class HomeController extends Controller {
    public function index(array $params = []): void {
        $shipment  = new ShipmentModel();
        $stats     = $shipment->getStats();
        $blog      = new BlogModel();
        $posts     = $blog->getFeatured(3);
        $db        = Database::getInstance();
        $faqs      = $db->query("SELECT * FROM faqs WHERE status=1 AND lang=? ORDER BY sort_order LIMIT 8",
                        )->fetchAll() ?: [];
        // Fallback using prepared statement
        $stmt = $db->prepare("SELECT * FROM faqs WHERE status=1 AND lang=? ORDER BY sort_order LIMIT 8");
        $stmt->execute([Lang::getInstance()->getLocale()]);
        $faqs = $stmt->fetchAll();

        $stmt2 = $db->prepare("SELECT * FROM partners WHERE status=1 ORDER BY sort_order LIMIT 20");
        $stmt2->execute();
        $partners = $stmt2->fetchAll();

        $this->view('public.home', [
            'title'    => Settings::get('meta_title', 'TrackXa - Professional Shipment Tracking'),
            'stats'    => $stats,
            'posts'    => $posts,
            'faqs'     => $faqs,
            'partners' => $partners,
        ]);
    }
}
