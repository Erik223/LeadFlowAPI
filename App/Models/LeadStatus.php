<?php
namespace App\Models;

enum LeadStatus: string {
    case NEW = "New";
    case CONTACTED = "Contacted";
    case CLOSED = "Closed";
    case LOST = "Lost";
}
?>