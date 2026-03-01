<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\User;
use App\Models\UserRole;
use PDO;

class UserRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::connection();
    }

    private function mapRowToEntity(array $row): User {
        return new User(
            name: $row['name'],
            email: $row['email'],
            passwordHash: $row['password_hash'],
            createdAt: $row['created_at'],
            updatedAt: $row['updated_at'],
            role: UserRole::from($row['role']),
            id: $row['id']
        );
    }

    public function findAll(): array {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute([]);

        $rows = $stmt->fetchAll();

        $all = [];
        foreach ($rows as $row) { $all[] = $this->mapRowToEntity($row); }

        return $all;
    }

    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch();
        if (!$row) return null;

        return $this->mapRowToEntity($row);
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $row = $stmt->fetch();
        if (!$row) return null;

        return $this->mapRowToEntity($row);
    }

    public function create(User $user): int {
        $data = $user->toArray();

        $stmt = $this->pdo->prepare("INSERT INTO users(name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['email'], $data['password_hash'], $data['role']]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, User $user): bool {
        $data = $user->toArray();

        $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, password_hash = ? WHERE id = ?");
        $success = $stmt->execute([$data['name'], $data['email'], $data['password_hash'], $id]);

        return $success;
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $success = $stmt->execute([$id]);

        return $success;
    }
}
?>