<?php
namespace App\Models;

use InvalidArgumentException;

class User {
    private string $name;
    private string $email;
    private string $passwordHash;
    private UserRole $role;

    public function __construct(string $name, string $email, string $passwordHash, UserRole $role = UserRole::USER) {
        $this->setName($name);
        $this->setEmail($email);

        $this->passwordHash = $passwordHash;
        $this->role = $role;
    }

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

    public function toArray(): array {
        return [
            "name" => $this->name,
            "email" => $this->email,
            "password_hash" => $this->passwordHash,
            "role" => $this->role->value
        ];
    }
}
?>