<?php
class AdminShipmentController extends Controller {
    private ShipmentModel        $model;
    private TrackingHistoryModel $history;

    public function __construct() {
        $this->model   = new ShipmentModel();
        $this->history = new TrackingHistoryModel();
    }

    public function index(array $params = []): void {
        $this->requireAdmin();
        $page   = max(1, (int)$this->get('page', 1));
        $q      = $this->get('q', '');
        $status = $this->get('status', '');

        if ($q) {
            $result = $this->model->searchAdmin($q, $page);
        } else {
            $where  = 'is_archived = 0';
            $args   = [];
            if ($status) { $where .= ' AND status = ?'; $args[] = $status; }
            $result = $this->model->paginate($page, 20, $where, $args, 'created_at DESC');
        }

        $this->view('admin.shipments.index', [
            'title'     => 'Shipments – Admin',
            'result'    => $result,
            'statuses'  => ShipmentModel::STATUSES,
            'q'         => $q,
            'status'    => $status,
        ], 'admin');
    }

    public function create(array $params = []): void {
        $this->requireAdmin();
        $this->view('admin.shipments.form', [
            'title'    => 'Create Shipment – Admin',
            'shipment' => null,
            'statuses' => ShipmentModel::STATUSES,
            'countries'=> $this->getCountries(),
            'carriers' => $this->getCarriers(),
            'methods'  => $this->getMethods(),
            'pkgtypes' => $this->getPkgTypes(),
            'websites' => $this->getWebsites(),
        ], 'admin');
    }

    public function store(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();

        $data = $this->collectFormData();
        $data['tracking_number'] = $this->model->generateTrackingNumber();
        $data['created_by']      = Session::getInstance()->get('admin_id');

        $id = $this->model->insert($data);
        if ($id) {
            $this->addHistoryEntry($id, $data['status'], $data['origin_city'] ?? '');
            $shipment = $this->model->find($id);
            if ($shipment && !empty($shipment['recipient_email'])) {
                EmailService::shipmentCreated($shipment);
            }
            (new AdminModel())->logActivity(
                Session::getInstance()->get('admin_id'), 'CREATE_SHIPMENT', 'shipments', $id
            );
            Session::getInstance()->flash('success', 'Shipment created with tracking number: ' . $data['tracking_number']);
        }
        $this->redirect(BASE_URL . '/admin/shipments');
    }

    public function edit(array $params = []): void {
        $this->requireAdmin();
        $shipment = $this->model->getWithDetails((int)$params['id']);
        if (!$shipment) $this->notFound();
        $history  = $this->history->getByShipment($shipment['id']);

        $this->view('admin.shipments.form', [
            'title'    => 'Edit Shipment – Admin',
            'shipment' => $shipment,
            'history'  => $history,
            'statuses' => ShipmentModel::STATUSES,
            'countries'=> $this->getCountries(),
            'carriers' => $this->getCarriers(),
            'methods'  => $this->getMethods(),
            'pkgtypes' => $this->getPkgTypes(),
            'websites' => $this->getWebsites(),
        ], 'admin');
    }

    public function update(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id       = (int)$params['id'];
        $shipment = $this->model->find($id);
        if (!$shipment) $this->notFound();

        $data = $this->collectFormData();
        $this->model->update($id, $data);

        if ($data['status'] !== $shipment['status']) {
            $this->addHistoryEntry($id, $data['status'], $data['current_location'] ?? '');
            $full = $this->model->find($id);
            if ($full) EmailService::shipmentUpdated($full, $data['status']);
        }

        (new AdminModel())->logActivity(Session::getInstance()->get('admin_id'), 'UPDATE_SHIPMENT', 'shipments', $id);
        Session::getInstance()->flash('success', 'Shipment updated successfully.');
        $this->redirect(BASE_URL . '/admin/shipments/' . $id . '/edit');
    }

    public function detail(array $params = []): void {
        $this->requireAdmin();
        $shipment = $this->model->getWithDetails((int)$params['id']);
        if (!$shipment) $this->notFound();
        $history  = $this->history->getByShipment($shipment['id']);
        $this->view('admin.shipments.view', [
            'title'      => 'Shipment #' . $shipment['tracking_number'],
            'shipment'   => $shipment,
            'history'    => $history,
            'statuses'   => ShipmentModel::STATUSES,
            'statusInfo' => ShipmentModel::STATUSES[$shipment['status']] ?? [],
        ], 'admin');
    }

    public function delete(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = (int)$params['id'];
        $this->model->delete($id);
        (new AdminModel())->logActivity(Session::getInstance()->get('admin_id'), 'DELETE_SHIPMENT', 'shipments', $id);
        Session::getInstance()->flash('success', 'Shipment deleted.');
        $this->redirect(BASE_URL . '/admin/shipments');
    }

    public function archive(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = (int)$params['id'];
        $this->model->update($id, ['is_archived' => 1]);
        Session::getInstance()->flash('success', 'Shipment archived.');
        $this->redirect(BASE_URL . '/admin/shipments');
    }

    public function duplicate(array $params = []): void {
        $this->requireAdmin();
        $this->validateCsrf();
        $original = $this->model->find((int)$params['id']);
        if (!$original) $this->notFound();
        unset($original['id'], $original['created_at'], $original['updated_at'], $original['actual_delivery']);
        $original['tracking_number'] = $this->model->generateTrackingNumber();
        $original['status']          = 'order_received';
        $original['created_by']      = Session::getInstance()->get('admin_id');
        $newId = $this->model->insert($original);
        Session::getInstance()->flash('success', 'Shipment duplicated with tracking: ' . $original['tracking_number']);
        $this->redirect(BASE_URL . '/admin/shipments/' . $newId . '/edit');
    }

    private function collectFormData(): array {
        $fields = [
            'reference_number','order_number','website_id','carrier_id','shipping_method_id',
            'package_type_id','status','sender_name','sender_phone','sender_email','sender_address',
            'sender_city','sender_country_id','sender_postal_code','recipient_name','recipient_phone',
            'recipient_email','recipient_address','recipient_city','recipient_country_id',
            'recipient_postal_code','origin_city','origin_country_id','destination_city',
            'destination_country_id','weight','weight_unit','length','width','height',
            'dimension_unit','declared_value','currency','description','special_instructions',
            'internal_notes','current_location','shipping_date','estimated_delivery','service_level',
        ];
        $data = [];
        foreach ($fields as $f) {
            $v = $this->post($f, null);
            $data[$f] = ($v === '') ? null : $v;
        }
        return $data;
    }

    private function addHistoryEntry(int $shipmentId, string $status, string $location): void {
        $statuses = ShipmentModel::STATUSES;
        $this->history->insert([
            'shipment_id' => $shipmentId,
            'status'      => $status,
            'location'    => $location,
            'description' => $statuses[$status]['label'] ?? $status,
            'occurred_at' => date('Y-m-d H:i:s'),
            'created_by'  => Session::getInstance()->get('admin_id'),
        ]);
    }

    private function getCountries(): array {
        return Database::getInstance()->query("SELECT id,name,code FROM countries ORDER BY name")->fetchAll();
    }
    private function getCarriers(): array {
        return Database::getInstance()->query("SELECT id,name FROM carriers WHERE status=1 ORDER BY name")->fetchAll();
    }
    private function getMethods(): array {
        return Database::getInstance()->query("SELECT id,name FROM shipping_methods WHERE status=1 ORDER BY name")->fetchAll();
    }
    private function getPkgTypes(): array {
        return Database::getInstance()->query("SELECT id,name FROM package_types WHERE status=1 ORDER BY name")->fetchAll();
    }
    private function getWebsites(): array {
        return Database::getInstance()->query("SELECT id,name FROM websites WHERE status='active' ORDER BY name")->fetchAll();
    }
}
