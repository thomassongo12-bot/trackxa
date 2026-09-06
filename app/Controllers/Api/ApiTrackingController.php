<?php
/**
 * TrackXa REST API – Public Tracking Endpoint
 */
class ApiTrackingController extends Controller {
    public function track(array $params = []): never {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        $number   = $params['number'] ?? '';
        $model    = new ShipmentModel();
        $histModel= new TrackingHistoryModel();

        if (empty($number)) {
            $this->json(['success' => false, 'error' => 'Tracking number required.'], 400);
        }

        $shipment = $model->findByTracking($number);
        if (!$shipment) {
            $this->json(['success' => false, 'error' => 'Shipment not found.'], 404);
        }

        $history   = $histModel->getByShipment($shipment['id']);
        $statusInfo= ShipmentModel::STATUSES[$shipment['status']] ?? [];

        $this->json([
            'success'  => true,
            'tracking' => [
                'tracking_number'    => $shipment['tracking_number'],
                'status'             => $shipment['status'],
                'status_label'       => $statusInfo['label'] ?? $shipment['status'],
                'status_color'       => $statusInfo['color'] ?? 'secondary',
                'current_location'   => $shipment['current_location'],
                'origin'             => $shipment['origin_city'],
                'destination'        => $shipment['destination_city'],
                'shipping_date'      => $shipment['shipping_date'],
                'estimated_delivery' => $shipment['estimated_delivery'],
                'actual_delivery'    => $shipment['actual_delivery'],
                'carrier'            => $shipment['carrier_name'],
                'recipient_name'     => $shipment['recipient_name'],
                'recipient_city'     => $shipment['recipient_city'],
                'weight'             => $shipment['weight'],
                'weight_unit'        => $shipment['weight_unit'],
                'package_type'       => $shipment['package_type_name'],
                'timeline'           => $history,
                'tracking_url'       => BASE_URL . '/track/' . $shipment['tracking_number'],
            ],
        ]);
    }
}
