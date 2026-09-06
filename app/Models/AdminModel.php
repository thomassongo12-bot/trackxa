<?php
class AdminModel extends Model {
    protected string $table = 'admins';

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function logActivity(int $adminId, string $action, string $model = '', int $modelId = 0, string $details = ''): void {
        $this->db->prepare("INSERT INTO admin_logs (admin_id,action,model,model_id,details,ip_address) VALUES (?,?,?,?,?,?)")
            ->execute([$adminId, $action, $model, $modelId ?: null, $details, Security::ip()]);
    }
}
