<?php
class TrackController extends Controller {
    private ShipmentModel       $shipment;
    private TrackingHistoryModel $history;

    public function __construct() {
        $this->shipment = new ShipmentModel();
        $this->history  = new TrackingHistoryModel();
    }

    public function index(array $params = []): void {
        $this->view('public.track', [
            'title'    => 'Track Your Shipment – ' . Settings::get('site_name', 'TrackXa'),
            'shipment' => null,
            'history'  => [],
            'error'    => null,
        ]);
    }

    public function search(array $params = []): void {
        $this->validateCsrf();
        $number = trim($this->post('tracking_number', ''));
        if (empty($number)) {
            $this->view('public.track', ['title' => 'Track Your Shipment', 'error' => 'Please enter a tracking number.', 'shipment' => null, 'history' => []]);
            return;
        }
        $this->redirectToTracking($number);
    }

    public function show(array $params = []): void {
        $number   = $params['number'] ?? '';
        $shipment = $this->shipment->findByTracking($number);

        if (!$shipment) {
            $this->view('public.track', [
                'title'    => 'Track Shipment – ' . Settings::get('site_name', 'TrackXa'),
                'error'    => "No shipment found for tracking number <strong>" . Security::e($number) . "</strong>.",
                'shipment' => null,
                'history'  => [],
                'number'   => $number,
            ]);
            return;
        }

        $history = $this->history->getByShipment($shipment['id']);

        // Log visitor
        try {
            Database::getInstance()->prepare(
                "INSERT INTO visitor_stats (date,page,ip_address,user_agent) VALUES (CURDATE(),?,?,?)"
            )->execute(['/track/' . $number, Security::ip(), $_SERVER['HTTP_USER_AGENT'] ?? '']);
        } catch (Exception $e) {}

        $statuses  = ShipmentModel::STATUSES;
        $statusInfo = $statuses[$shipment['status']] ?? ['label' => $shipment['status'], 'color' => 'secondary', 'icon' => 'fa-circle'];

        $this->view('public.track_result', [
            'title'      => 'Tracking: ' . $shipment['tracking_number'] . ' – ' . Settings::get('site_name', 'TrackXa'),
            'shipment'   => $shipment,
            'history'    => $history,
            'statusInfo' => $statusInfo,
            'statuses'   => $statuses,
            'ogTitle'    => 'Track Shipment ' . $shipment['tracking_number'],
            'ogDesc'     => 'Current status: ' . $statusInfo['label'] . '. Track your package in real time.',
        ]);
    }

    private function redirectToTracking(string $number): never {
        $this->redirect(BASE_URL . '/track/' . urlencode(preg_replace('/[^a-zA-Z0-9\-_]/', '', $number)));
    }
}
