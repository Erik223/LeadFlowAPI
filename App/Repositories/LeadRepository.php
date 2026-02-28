<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Lead;
use App\Models\LeadStatus;
use PDO;

class LeadRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::connection();
    }

    private function mapRowToEntity(array $row): Lead {
        return new Lead(
            name: $row['name'],
            company: $row['company'],
            userId: $row['user_id'],
            email: $row['email'],
            phone: $row['phone'],
            source: $row['source'],
            notes: $row['notes'],
            status: LeadStatus::from($row['status'])
        );
    }

    public function findAll(int $limit = 20, int $offset = 0): array {
        $stmt = $this->pdo->prepare("SELECT * FROM leads ORDER BY created_at DESC LIMIT ? OFFSET ?");

        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $all = [];
        foreach ($rows as $row) { $all[] = $this->mapRowToEntity($row); }

        return $all;
    }

    public function findById(int $id): ?Lead {
        $stmt = $this->pdo->prepare("SELECT * FROM leads WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch();
        if (!$row) return null;

        return $this->mapRowToEntity($row);
    }

    public function findByUser(int $userId, int $limit = 20, int $offset = 0): array {
        $stmt = $this->pdo->prepare("SELECT * FROM leads WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");

        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $all = [];
        foreach ($rows as $row) { $all[] = $this->mapRowToEntity($row); }

        return $all;
    }

    public function create(Lead $lead): int {
        $data = $lead->toArray();

        $stmt = $this->pdo->prepare("INSERT INTO leads(name, company, email, phone, source, status, notes, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['company'], $data['email'], $data['phone'], $data['source'], $data['status'], $data['notes'], $data['user_id']]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, Lead $lead): bool {
        $data = $lead->toArray();

        $stmt = $this->pdo->prepare("UPDATE leads SET name = ?, company = ?, email = ?, phone = ?, source = ?, status = ?, notes = ? WHERE id = ?");
        $success = $stmt->execute([$data['name'], $data['company'], $data['email'], $data['phone'], $data['source'], $data['status'], $data['notes'], $id]);

        return $success;
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM leads WHERE id = ?");
        $success = $stmt->execute([$id]);

        return $success;
    }

    public function countByUser(int $userId): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM leads WHERE user_id = ?");
        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }

    public function countAll(): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM leads");
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
?>