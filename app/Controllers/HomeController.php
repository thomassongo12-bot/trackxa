<?php
class HomeController extends Controller {
    public function index(array $params = []): void {
        $shipment  = new ShipmentModel();
        $stats     = $shipment->getStats();
        $blog      = new BlogModel();
        $posts     = $blog->getFeatured(3);
        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM faqs WHERE status=1 AND lang=? ORDER BY sort_order LIMIT 8");
        $stmt->execute([Lang::getInstance()->getLocale()]);
        $faqs = $stmt->fetchAll();

        $stmt2 = $db->prepare("SELECT * FROM partners WHERE status=1 ORDER BY sort_order LIMIT 20");
        $stmt2->execute();
        $partners = $stmt2->fetchAll();

        $stmt3 = $db->prepare("SELECT id, name, code, logo, website FROM carriers WHERE status=1 ORDER BY name ASC");
        $stmt3->execute();
        $carriers = $stmt3->fetchAll();

        // Fallback: si la table est vide, afficher les transporteurs par défaut
        if (empty($carriers)) {
            $carriers = [
                ['id'=>0,'name'=>'DHL Express',  'code'=>'DHL',       'logo'=>null,'website'=>'https://www.dhl.com'],
                ['id'=>0,'name'=>'FedEx',         'code'=>'FEDEX',     'logo'=>null,'website'=>'https://www.fedex.com'],
                ['id'=>0,'name'=>'UPS',           'code'=>'UPS',       'logo'=>null,'website'=>'https://www.ups.com'],
                ['id'=>0,'name'=>'USPS',          'code'=>'USPS',      'logo'=>null,'website'=>'https://www.usps.com'],
                ['id'=>0,'name'=>'TNT',           'code'=>'TNT',       'logo'=>null,'website'=>'https://www.tnt.com'],
                ['id'=>0,'name'=>'Aramex',        'code'=>'ARAMEX',    'logo'=>null,'website'=>'https://www.aramex.com'],
                ['id'=>0,'name'=>'DPD',           'code'=>'DPD',       'logo'=>null,'website'=>'https://www.dpd.com'],
                ['id'=>0,'name'=>'GLS',           'code'=>'GLS',       'logo'=>null,'website'=>'https://gls-group.eu'],
                ['id'=>0,'name'=>'Royal Mail',    'code'=>'ROYALMAIL', 'logo'=>null,'website'=>'https://www.royalmail.com'],
                ['id'=>0,'name'=>'La Poste',      'code'=>'LAPOSTE',   'logo'=>null,'website'=>'https://www.laposte.fr'],
            ];
        }

        $this->view('public.home', [
            'title'    => Settings::get('meta_title', 'TrackXa - Professional Shipment Tracking'),
            'stats'    => $stats,
            'posts'    => $posts,
            'faqs'     => $faqs,
            'partners' => $partners,
            'carriers' => $carriers,
        ]);
    }
}
