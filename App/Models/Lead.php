<?php
namespace App\Models;

use InvalidArgumentException;

class Lead {
    private string $name;
    private string $company;
    private ?string $email;
    private ?string $phone;
    private ?string $source;
    private LeadStatus $status;
    private ?string $notes;
    private int $userId;

    public function __construct(string $name, string $company, int $userId, ?string $email = null, ?string $phone = null, ?string $source = null, ?string $notes = null, LeadStatus $status = LeadStatus::NEW,) {
        $this->setName($name);
        $this->setCompany($company);

        $this->userId = $userId;
        $this->email = $email;
        $this->phone = $phone;
        $this->source = $source;
        $this->notes = $notes;
        $this->status = $status;
    }

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

    public function toArray(): array {
        return [
            "name" => $this->name,
            "company" => $this->company,
            "email" => $this->email,
            "phone" => $this->phone,
            "source" => $this->source,
            "status" => $this->status->value,
            "notes" => $this->notes,
            "user_id" => $this->userId
        ];
    }
}
?>