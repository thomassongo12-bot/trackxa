<?php
/**
 * TrackXa REST API – Shipments
 */
class ApiShipmentController extends Controller {
    private ShipmentModel        $model;
    private TrackingHistoryModel $history;
    private ApiKeyModel          $keyModel;

    public function __construct() {
        $this->model    = new ShipmentModel();
        $this->history  = new TrackingHistoryModel();
        $this->keyModel = new ApiKeyModel();
    }

    private function auth(): array {
        return (new ApiAuthMiddleware())->handle();
    }

    private function body(): array {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?? [];
    }

    public function ping(array $params = []): never {
        $this->json(['success' => true, 'message' => 'TrackXa API v1 is running', 'timestamp' => time()]);
    }

    public function create(array $params = []): never {
        $ctx  = $this->auth();
        $key  = $ctx['key'];
        $body = $this->body();

        // Required fields
        $required = ['recipient_name', 'recipient_address', 'recipient_city'];
        foreach ($required as $f) {
            if (empty($body[$f])) {
                $this->json(['success' => false, 'error' => "Field '{$f}' is required."], 422);
            }
        }

        $tracking = $this->model->generateTrackingNumber();
        $data = [
            'tracking_number'      => $tracking,
            'reference_number'     => $body['reference_number'] ?? null,
            'order_number'         => $body['order_number']     ?? null,
            'website_id'           => $key['website_id'],
            'carrier_id'           => $body['carrier_id']       ?? null,
            'shipping_method_id'   => $body['shipping_method_id'] ?? null,
            'package_type_id'      => $body['package_type_id']  ?? null,
            'status'               => 'shipment_created',
            'sender_name'          => $body['sender_name']      ?? null,
            'sender_phone'         => $body['sender_phone']     ?? null,
            'sender_email'         => $body['sender_email']     ?? null,
            'sender_address'       => $body['sender_address']   ?? null,
            'sender_city'          => $body['sender_city']      ?? null,
            'recipient_name'       => Security::sanitize($body['recipient_name']),
            'recipient_phone'      => $body['recipient_phone']  ?? null,
            'recipient_email'      => $body['recipient_email']  ?? null,
            'recipient_address'    => Security::sanitize($body['recipient_address']),
            'recipient_city'       => Security::sanitize($body['recipient_city']),
            'origin_city'          => $body['origin_city']      ?? null,
            'destination_city'     => $body['destination_city'] ?? null,
            'weight'               => $body['weight']           ?? null,
            'weight_unit'          => $body['weight_unit']      ?? 'kg',
            'description'          => $body['description']      ?? null,
            'special_instructions' => $body['special_instructions'] ?? null,
            'shipping_date'        => $body['shipping_date']    ?? date('Y-m-d'),
            'estimated_delivery'   => $body['estimated_delivery'] ?? null,
            'declared_value'       => $body['declared_value']   ?? null,
            'currency'             => $body['currency']         ?? 'USD',
            'service_level'        => $body['service_level']    ?? null,
        ];

        $id = $this->model->insert($data);

        // Initial history
        $this->history->insert([
            'shipment_id' => $id,
            'status'      => 'shipment_created',
            'location'    => $data['origin_city'] ?? '',
            'description' => 'Shipment created via API',
            'occurred_at' => date('Y-m-d H:i:s'),
        ]);

        // Send email notification
        if (!empty($data['recipient_email'])) {
            EmailService::shipmentCreated(array_merge($data, ['id' => $id]));
        }

        $this->logApi($ctx, 201);
        $this->json([
            'success'          => true,
            'tracking_number'  => $tracking,
            'tracking_url'     => BASE_URL . '/track/' . $tracking,
            'shipment_id'      => $id,
            'estimated_delivery' => $data['estimated_delivery'],
        ], 201);
    }

    public function show(array $params = []): never {
        $ctx  = $this->auth();
        $id   = (int)$params['id'];
        $ship = $this->model->getWithDetails($id);

        if (!$ship || $ship['website_id'] != $ctx['key']['website_id']) {
            $this->json(['success' => false, 'error' => 'Shipment not found.'], 404);
        }

        $this->logApi($ctx, 200);
        $this->json(['success' => true, 'data' => $this->formatShipment($ship)]);
    }

    public function update(array $params = []): never {
        $ctx  = $this->auth();
        $id   = (int)$params['id'];
        $ship = $this->model->find($id);

        if (!$ship || $ship['website_id'] != $ctx['key']['website_id']) {
            $this->json(['success' => false, 'error' => 'Shipment not found.'], 404);
        }

        $body   = $this->body();
        $fields = ['recipient_name','recipient_phone','recipient_email','recipient_address',
                   'recipient_city','description','special_instructions','estimated_delivery',
                   'current_location','service_level','weight','declared_value'];
        $data   = [];
        foreach ($fields as $f) {
            if (isset($body[$f])) $data[$f] = Security::sanitize($body[$f]);
        }
        if ($data) $this->model->update($id, $data);

        $this->logApi($ctx, 200);
        $this->json(['success' => true, 'message' => 'Shipment updated.']);
    }

    public function updateStatus(array $params = []): never {
        $ctx  = $this->auth();
        $id   = (int)$params['id'];
        $ship = $this->model->find($id);

        if (!$ship || $ship['website_id'] != $ctx['key']['website_id']) {
            $this->json(['success' => false, 'error' => 'Shipment not found.'], 404);
        }

        $body     = $this->body();
        $status   = $body['status']   ?? '';
        $location = $body['location'] ?? '';
        $desc     = $body['description'] ?? (ShipmentModel::STATUSES[$status]['label'] ?? $status);
        $notes    = $body['notes']    ?? null;
        $at       = $body['occurred_at'] ?? date('Y-m-d H:i:s');

        $validStatuses = array_keys(ShipmentModel::STATUSES);
        if (!in_array($status, $validStatuses)) {
            $this->json(['success' => false, 'error' => 'Invalid status.', 'valid' => $validStatuses], 422);
        }

        $this->history->insert([
            'shipment_id'    => $id,
            'status'         => $status,
            'location'       => $location,
            'description'    => $desc,
            'operator_notes' => $notes,
            'occurred_at'    => $at,
        ]);

        $updateData = ['status' => $status, 'current_location' => $location];
        if ($status === 'delivered') $updateData['actual_delivery'] = $at;
        $this->model->update($id, $updateData);

        EmailService::shipmentUpdated($ship, $status);

        if ($ship['website_id']) {
            WebhookService::dispatch($ship['website_id'], 'shipment.status_updated', [
                'tracking_number' => $ship['tracking_number'],
                'status'          => $status,
                'location'        => $location,
            ]);
        }

        $this->logApi($ctx, 200);
        $this->json(['success' => true, 'message' => 'Status updated.', 'status' => $status]);
    }

    public function cancel(array $params = []): never {
        $ctx  = $this->auth();
        $id   = (int)$params['id'];
        $ship = $this->model->find($id);

        if (!$ship || $ship['website_id'] != $ctx['key']['website_id']) {
            $this->json(['success' => false, 'error' => 'Shipment not found.'], 404);
        }

        $this->model->update($id, ['status' => 'cancelled']);
        $this->history->insert([
            'shipment_id' => $id,
            'status'      => 'cancelled',
            'description' => 'Cancelled via API',
            'occurred_at' => date('Y-m-d H:i:s'),
        ]);

        $this->logApi($ctx, 200);
        $this->json(['success' => true, 'message' => 'Shipment cancelled.']);
    }

    public function timeline(array $params = []): never {
        $ctx  = $this->auth();
        $id   = (int)$params['id'];
        $ship = $this->model->find($id);

        if (!$ship || $ship['website_id'] != $ctx['key']['website_id']) {
            $this->json(['success' => false, 'error' => 'Shipment not found.'], 404);
        }

        $timeline = $this->history->getByShipment($id);
        $this->logApi($ctx, 200);
        $this->json(['success' => true, 'tracking_number' => $ship['tracking_number'], 'timeline' => $timeline]);
    }

    public function validate(array $params = []): never {
        $this->auth();
        $body   = $this->body();
        $number = $body['tracking_number'] ?? '';
        $ship   = $this->model->findByTracking($number);
        $this->json([
            'success' => true,
            'valid'   => (bool)$ship,
            'status'  => $ship ? $ship['status'] : null,
        ]);
    }

    private function formatShipment(array $s): array {
        return [
            'id'                 => $s['id'],
            'tracking_number'    => $s['tracking_number'],
            'reference_number'   => $s['reference_number'],
            'order_number'       => $s['order_number'],
            'status'             => $s['status'],
            'status_label'       => ShipmentModel::STATUSES[$s['status']]['label'] ?? $s['status'],
            'current_location'   => $s['current_location'],
            'carrier'            => $s['carrier_name'],
            'shipping_method'    => $s['shipping_method_name'],
            'recipient'          => [
                'name'    => $s['recipient_name'],
                'city'    => $s['recipient_city'],
                'country' => $s['recipient_country_name'],
            ],
            'origin'             => $s['origin_city'],
            'destination'        => $s['destination_city'],
            'shipping_date'      => $s['shipping_date'],
            'estimated_delivery' => $s['estimated_delivery'],
            'actual_delivery'    => $s['actual_delivery'],
            'tracking_url'       => BASE_URL . '/track/' . $s['tracking_number'],
            'created_at'         => $s['created_at'],
            'updated_at'         => $s['updated_at'],
        ];
    }

    private function logApi(array $ctx, int $code): void {
        $elapsed = round((microtime(true) - $ctx['start']) * 1000, 2);
        $this->keyModel->log([
            'api_key_id' => $ctx['key']['id'],
            'endpoint'   => $_SERVER['REQUEST_URI'] ?? '',
            'method'     => $_SERVER['REQUEST_METHOD'] ?? 'POST',
            'request'    => file_get_contents('php://input'),
            'code'       => $code,
            'time'       => $elapsed,
        ]);
    }
}
