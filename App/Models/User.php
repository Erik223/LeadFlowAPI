<?php
namespace App\Models;

use InvalidArgumentException;

class User {
    private ?int $id;
    private string $name;
    private string $email;
    private string $passwordHash;
    private ?string $createdAt;
    private ?string $updatedAt;
    private UserRole $role;

    public function __construct(string $name, string $email, string $passwordHash, ?string $createdAt = null, ?string $updatedAt = null, UserRole $role = UserRole::USER, ?int $id = null) {
        $this->setName($name);
        $this->setEmail($email);

        $this->passwordHash = $passwordHash;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->role = $role;
        $this->id = $id;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPasswordHash(): string { return $this->passwordHash; }
    public function getRole(): UserRole { return $this->role; }

    private function setName(string $name): void {
        if (trim($name) === "") {
            throw new InvalidArgumentException("Name cannot be empty.");
        }
        $this->name = $name;
    }

    private function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email.");
        }
        $this->email = $email;
    }

    public function copy(?string $name = null, ?string $email = null, ?string $passwordHash = null, ?UserRole $role = null): User {
        $copyName = $name ?? $this->name;
        $copyEmail = $email ?? $this->email;
        $copyPasswordHash = $passwordHash ?? $this->passwordHash;
        $copyRole = $role ?? $this->role;

        return new User(
            $copyName, 
            $copyEmail, 
            $copyPasswordHash, 
            $this->createdAt,
            $this->updatedAt,
            $copyRole, 
            $this->id
        );
    }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "password_hash" => $this->passwordHash,
            "created_at" => $this->createdAt,
            "updated_at" => $this->updatedAt,
            "role" => $this->role->value
        ];
    }
}
?>