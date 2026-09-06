<?php
class AdminTrackingController extends Controller {
    private TrackingHistoryModel $history;
    private ShipmentModel        $shipment;

    public function __construct() {
        $this->history  = new TrackingHistoryModel();
        $this->shipment = new ShipmentModel();
    }

    public function add(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();

        $shipmentId = (int)$this->post('shipment_id');
        $shipment   = $this->shipment->find($shipmentId);
        if (!$shipment) { $this->json(['error' => 'Shipment not found'], 404); }

        $status      = $this->post('status', '');
        $location    = $this->post('location', '');
        $description = $this->post('description', '');
        $notes       = $this->post('operator_notes', '');
        $occurredAt  = $this->post('occurred_at', date('Y-m-d H:i:s'));

        $id = $this->history->insert([
            'shipment_id'    => $shipmentId,
            'status'         => $status,
            'location'       => $location,
            'description'    => $description ?: (ShipmentModel::STATUSES[$status]['label'] ?? $status),
            'operator_notes' => $notes,
            'occurred_at'    => $occurredAt,
            'created_by'     => Session::getInstance()->get('admin_id'),
        ]);

        // Update shipment current status
        $this->shipment->update($shipmentId, [
            'status'           => $status,
            'current_location' => $location,
        ]);

        if ($status === 'delivered') {
            $this->shipment->update($shipmentId, ['actual_delivery' => $occurredAt]);
        }

        // Send email notification
        $full = $this->shipment->find($shipmentId);
        if ($full) EmailService::shipmentUpdated($full, $status);

        // Webhook
        if ($shipment['website_id']) {
            WebhookService::dispatch($shipment['website_id'], 'shipment.updated', [
                'tracking_number' => $shipment['tracking_number'],
                'status'          => $status,
                'location'        => $location,
            ]);
        }

        (new AdminModel())->logActivity(
            Session::getInstance()->get('admin_id'), 'ADD_TRACKING', 'shipments', $shipmentId
        );

        Session::getInstance()->flash('success', 'Tracking update added.');
        $this->redirect(BASE_URL . '/admin/shipments/' . $shipmentId . '/edit');
    }

    public function edit(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id   = (int)$params['id'];
        $data = [
            'status'         => $this->post('status', ''),
            'location'       => $this->post('location', ''),
            'description'    => $this->post('description', ''),
            'operator_notes' => $this->post('operator_notes', ''),
            'occurred_at'    => $this->post('occurred_at', date('Y-m-d H:i:s')),
        ];
        $this->history->update($id, $data);
        Session::getInstance()->flash('success', 'Tracking entry updated.');
        $row = $this->history->find($id);
        $this->redirect(BASE_URL . '/admin/shipments/' . ($row['shipment_id'] ?? '') . '/edit');
    }

    public function delete(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id  = (int)$params['id'];
        $row = $this->history->find($id);
        $this->history->delete($id);
        Session::getInstance()->flash('success', 'Tracking entry deleted.');
        $this->redirect(BASE_URL . '/admin/shipments/' . ($row['shipment_id'] ?? '') . '/edit');
    }
}
