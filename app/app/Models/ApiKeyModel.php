<?php
class ApiKeyModel extends Model {
    protected string $table = 'api_keys';

    public function findByKey(string $apiKey): array|false {
        $stmt = $this->db->prepare("
            SELECT k.*, w.name AS website_name, w.domain
            FROM api_keys k
            LEFT JOIN websites w ON k.website_id = w.id
            WHERE k.api_key = ? AND k.status = 'active'
            LIMIT 1
        ");
        $stmt->execute([$apiKey]);
        return $stmt->fetch();
    }

    public function generateKeyPair(int $websiteId): array {
        $key    = bin2hex(random_bytes(24));
        $secret = bin2hex(random_bytes(48));
        $this->insert([
            'website_id' => $websiteId,
            'api_key'    => $key,
            'api_secret' => hash('sha256', $secret),
            'status'     => 'active',
        ]);
        return ['api_key' => $key, 'api_secret' => $secret];
    }

    public function incrementUsage(int $id): void {
        $this->db->prepare("UPDATE api_keys SET calls_today = calls_today+1, calls_total = calls_total+1, last_used_at = NOW(), last_ip = ? WHERE id = ?")
            ->execute([Security::ip(), $id]);
    }

    public function checkRateLimit(int $id, int $limit): bool {
        $stmt = $this->db->prepare("SELECT calls_today FROM api_keys WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row && $row['calls_today'] < $limit;
    }

    public function resetDailyCounts(): void {
        $this->db->exec("UPDATE api_keys SET calls_today = 0");
    }

    public function log(array $data): void {
        $this->db->prepare("INSERT INTO api_logs (api_key_id,endpoint,method,request_body,response_code,response_body,ip_address,execution_time) VALUES (?,?,?,?,?,?,?,?)")
            ->execute([
                $data['api_key_id'] ?? null,
                $data['endpoint']   ?? '',
                $data['method']     ?? 'GET',
                $data['request']    ?? null,
                $data['code']       ?? 200,
                $data['response']   ?? null,
                Security::ip(),
                $data['time']       ?? 0,
            ]);
    }
}
