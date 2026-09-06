<?php
/**
 * TrackXa - Tracking History Model
 */
class TrackingHistoryModel extends Model {
    protected string $table = 'tracking_history';

    public function getByShipment(int $shipmentId): array {
        return $this->query("
            SELECT th.*, a.name AS operator_name
            FROM tracking_history th
            LEFT JOIN admins a ON th.created_by = a.id
            WHERE th.shipment_id = ?
            ORDER BY th.occurred_at ASC
        ", [$shipmentId])->fetchAll();
    }

    public function getLatestByShipment(int $shipmentId): array|false {
        $stmt = $this->db->prepare("
            SELECT * FROM tracking_history
            WHERE shipment_id = ?
            ORDER BY occurred_at DESC LIMIT 1
        ");
        $stmt->execute([$shipmentId]);
        return $stmt->fetch();
    }
}
