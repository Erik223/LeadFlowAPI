<?php
namespace App\Models;

class Lead {
    public int $id;
    public string $name;
    public string $company;
    public string $email;
    public string $phone;
    public string $source;
    public string $status;
    public string $notes;
    public int $user_id;
}
?>