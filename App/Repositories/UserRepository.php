<?php
namespace App\Repositories;

use App\Core\Database;
use PDO;

class UserRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::connection();
    }

    public function findAll(): array {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute([]);

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare("INSERT INTO users(name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['email'], $data['password']]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $success = $stmt->execute([$data['name'], $data['email'], $id]);

        return $success;
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $success = $stmt->execute([$id]);

        return $success;
    }
}
?>