<?php
class WebsiteModel extends Model {
    protected string $table = 'websites';

    public function getWithKeys(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT w.*, GROUP_CONCAT(k.api_key) AS keys
            FROM websites w
            LEFT JOIN api_keys k ON k.website_id = w.id AND k.status = 'active'
            WHERE w.id = ?
            GROUP BY w.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
