<?php
namespace App\Models;

enum UserRole: string {
    case USER = "User";
    case ADMIN = "Admin";
}
?>