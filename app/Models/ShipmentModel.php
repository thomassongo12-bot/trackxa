<?php
/**
 * TrackXa - Shipment Model
 */
class ShipmentModel extends Model {
    protected string $table = 'shipments';

    public const STATUSES = [
        'order_received'   => ['label' => 'Order Received',       'color' => 'secondary', 'icon' => 'fa-inbox'],
        'shipment_created' => ['label' => 'Shipment Created',     'color' => 'info',      'icon' => 'fa-box'],
        'preparing'        => ['label' => 'Preparing Shipment',   'color' => 'info',      'icon' => 'fa-boxes-packing'],
        'picked_up'        => ['label' => 'Picked Up',            'color' => 'primary',   'icon' => 'fa-hand-holding-box'],
        'at_warehouse'     => ['label' => 'At Warehouse',         'color' => 'primary',   'icon' => 'fa-warehouse'],
        'in_transit'       => ['label' => 'In Transit',           'color' => 'primary',   'icon' => 'fa-truck'],
        'arrived_airport'  => ['label' => 'Arrived at Airport',   'color' => 'primary',   'icon' => 'fa-plane-arrival'],
        'departed_airport' => ['label' => 'Departed Airport',     'color' => 'primary',   'icon' => 'fa-plane-departure'],
        'customs_clearance'=> ['label' => 'Customs Clearance',    'color' => 'warning',   'icon' => 'fa-file-shield'],
        'released_customs' => ['label' => 'Released from Customs','color' => 'success',   'icon' => 'fa-check-circle'],
        'out_for_delivery' => ['label' => 'Out for Delivery',     'color' => 'success',   'icon' => 'fa-truck-fast'],
        'delivered'        => ['label' => 'Delivered',            'color' => 'success',   'icon' => 'fa-circle-check'],
        'delivery_failed'  => ['label' => 'Delivery Failed',      'color' => 'danger',    'icon' => 'fa-circle-xmark'],
        'returned'         => ['label' => 'Returned to Sender',   'color' => 'danger',    'icon' => 'fa-rotate-left'],
        'cancelled'        => ['label' => 'Cancelled',            'color' => 'danger',    'icon' => 'fa-ban'],
        'delayed'          => ['label' => 'Delayed',              'color' => 'warning',   'icon' => 'fa-clock'],
        'on_hold'          => ['label' => 'On Hold',              'color' => 'warning',   'icon' => 'fa-pause-circle'],
    ];

    public function findByTracking(string $tracking): array|false {
        $stmt = $this->db->prepare("
            SELECT s.*,
                   c.name AS carrier_name, c.logo AS carrier_logo,
                   sm.name AS shipping_method_name,
                   pt.name AS package_type_name,
                   oc.name AS origin_country_name, oc.code AS origin_country_code,
                   dc.name AS destination_country_name, dc.code AS destination_country_code,
                   rc.name AS recipient_country_name,
                   w.name AS website_name
            FROM shipments s
            LEFT JOIN carriers c ON s.carrier_id = c.id
            LEFT JOIN shipping_methods sm ON s.shipping_method_id = sm.id
            LEFT JOIN package_types pt ON s.package_type_id = pt.id
            LEFT JOIN countries oc ON s.origin_country_id = oc.id
            LEFT JOIN countries dc ON s.destination_country_id = dc.id
            LEFT JOIN countries rc ON s.recipient_country_id = rc.id
            LEFT JOIN websites w ON s.website_id = w.id
            WHERE s.tracking_number = ? OR s.reference_number = ? OR s.order_number = ?
            LIMIT 1
        ");
        $stmt->execute([$tracking, $tracking, $tracking]);
        return $stmt->fetch();
    }

    public function getWithDetails(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT s.*,
                   c.name AS carrier_name, c.logo AS carrier_logo,
                   sm.name AS shipping_method_name,
                   pt.name AS package_type_name,
                   oc.name AS origin_country_name,
                   dc.name AS destination_country_name,
                   rc.name AS recipient_country_name,
                   sc.name AS sender_country_name,
                   w.name AS website_name
            FROM shipments s
            LEFT JOIN carriers c ON s.carrier_id = c.id
            LEFT JOIN shipping_methods sm ON s.shipping_method_id = sm.id
            LEFT JOIN package_types pt ON s.package_type_id = pt.id
            LEFT JOIN countries oc ON s.origin_country_id = oc.id
            LEFT JOIN countries dc ON s.destination_country_id = dc.id
            LEFT JOIN countries rc ON s.recipient_country_id = rc.id
            LEFT JOIN countries sc ON s.sender_country_id = sc.id
            LEFT JOIN websites w ON s.website_id = w.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function generateTrackingNumber(): string {
        $prefix = Settings::get('tracking_prefix', 'TXA');
        do {
            $number = $prefix . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 12));
            $exists = $this->count('tracking_number = ?', [$number]);
        } while ($exists > 0);
        return $number;
    }

    public function getStats(): array {
        $stmt = $this->db->query("
            SELECT
                COUNT(*) AS total,
                SUM(DATE(created_at) = CURDATE()) AS today,
                SUM(status = 'delivered') AS delivered,
                SUM(status = 'in_transit') AS in_transit_count,
                SUM(status IN ('order_received','shipment_created','preparing')) AS pending,
                SUM(status = 'delayed') AS delayed_count
            FROM shipments WHERE is_archived = 0
        ");
        $row = $stmt->fetch();
        // Normalize keys for views that use 'in_transit' and 'delayed'
        if ($row) {
            $row['in_transit'] = $row['in_transit_count'] ?? 0;
            $row['delayed']    = $row['delayed_count']    ?? 0;
        }
        return $row ?: [];
    }

    public function getLatest(int $limit = 10): array {
        return $this->query("
            SELECT s.*, c.name AS carrier_name, w.name AS website_name
            FROM shipments s
            LEFT JOIN carriers c ON s.carrier_id = c.id
            LEFT JOIN websites w ON s.website_id = w.id
            WHERE s.is_archived = 0
            ORDER BY s.created_at DESC LIMIT ?
        ", [$limit])->fetchAll();
    }

    public function searchAdmin(string $q, int $page = 1, int $per = 20): array {
        $like  = "%{$q}%";
        $where = "is_archived = 0 AND (tracking_number LIKE ? OR reference_number LIKE ? OR recipient_name LIKE ? OR order_number LIKE ?)";
        return $this->paginate($page, $per, $where, [$like,$like,$like,$like]);
    }
}
