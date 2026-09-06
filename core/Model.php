<?php
/**
 * TrackXa - Base Model
 */
abstract class Model {
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAll(string $where = '', array $params = [], string $order = '', int $limit = 0, int $offset = 0): array {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($where) $sql .= " WHERE {$where}";
        if ($order) $sql .= " ORDER BY {$order}";
        if ($limit)  $sql .= " LIMIT {$limit}";
        if ($offset) $sql .= " OFFSET {$offset}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count(string $where = '', array $params = []): int {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if ($where) $sql .= " WHERE {$where}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function insert(array $data): int|false {
        $cols = implode(',', array_map(fn($c) => "`{$c}`", array_keys($data)));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO `{$this->table}` ({$cols}) VALUES ({$placeholders})");
        $stmt->execute(array_values($data));
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = implode(',', array_map(fn($c) => "`{$c}` = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE `{$this->table}` SET {$set} WHERE `{$this->primaryKey}` = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?");
        return $stmt->execute([$id]);
    }

    public function query(string $sql, array $params = []): \PDOStatement {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function paginate(int $page, int $perPage, string $where = '', array $params = [], string $order = 'id DESC'): array {
        $total  = $this->count($where, $params);
        $pages  = (int)ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $rows   = $this->findAll($where, $params, $order, $perPage, $offset);
        return ['data' => $rows, 'total' => $total, 'pages' => $pages, 'page' => $page, 'per_page' => $perPage];
    }
}
