<?php
namespace App\Models;

use InvalidArgumentException;

class Lead {
    private ?int $id;
    private string $name;
    private string $company;
    private ?string $email;
    private ?string $phone;
    private ?string $source;
    private ?string $notes;
    private ?string $createdAt;
    private ?string $updatedAt;
    private LeadStatus $status;
    private int $userId;

    public function __construct(string $name, string $company, int $userId, ?string $email = null, ?string $phone = null, ?string $source = null, ?string $notes = null, ?string $createdAt = null, ?string $updatedAt = null, LeadStatus $status = LeadStatus::NEW, ?int $id = null) {
        $this->setName($name);
        $this->setCompany($company);

        $this->userId = $userId;
        $this->email = $email;
        $this->phone = $phone;
        $this->source = $source;
        $this->notes = $notes;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->status = $status;
        $this->id = $id;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCompany(): string { return $this->company; }
    public function getUserId(): int { return $this->userId; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): string { return $this->phone; }
    public function getSource(): string { return $this->source; }
    public function getStatus(): LeadStatus { return $this->status; }
    public function getNotes(): string { return $this->notes; }

    private function setName(string $name): void {
        if (trim($name) === "") {
            throw new InvalidArgumentException("Name cannot be empty.");
        }
        $this->name = $name;
    }

    private function setCompany(string $company): void {
        if (trim($company) === "") {
            throw new InvalidArgumentException("Company cannot be empty.");
        }
        $this->company = $company;
    }

    public function copy(?string $name = null, ?string $company = null, ?int $userId = null, ?string $email = null, ?string $phone = null, ?string $source = null, ?string $notes = null, ?LeadStatus $status = null): Lead {
        $copyName = $name ?? $this->name;
        $copyCompany = $company ?? $this->company;
        $copyUserId = $userId ?? $this->userId;
        $copyEmail = $email ?? $this->email;
        $copyPhone = $phone ?? $this->phone;
        $copySource = $source ?? $this->source;
        $copyNotes = $notes ?? $this->notes;
        $copyStatus = $status ?? $this->status;

        return new Lead(
            $copyName, 
            $copyCompany, 
            $copyUserId, 
            $copyEmail, 
            $copyPhone, 
            $copySource, 
            $copyNotes, 
            $this->createdAt,
            $this->updatedAt,
            $copyStatus, 
            $this->id
        );
    }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "company" => $this->company,
            "email" => $this->email,
            "phone" => $this->phone,
            "source" => $this->source,
            "notes" => $this->notes,
            "created_at" => $this->createdAt,
            "updated_at" => $this->updatedAt,
            "status" => $this->status->value,
            "user_id" => $this->userId
        ];
    }
}
?>