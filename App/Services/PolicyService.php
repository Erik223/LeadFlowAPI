<?php
namespace App\Services;

class PolicyService{
    public static function canViewAny(array $authUser): bool {
        return $authUser['role'] === "Admin";
    }

    public static function canView(array $authUser, int $targetUserId): bool {
        if ($authUser['role'] === "Admin") return true;

        return $authUser['sub'] === $targetUserId;
    }

    public static function canUpdate(array $authUser, int $targetUserId): bool {
        if ($authUser['role'] === "Admin") return true;

        return $authUser['sub'] === $targetUserId;
    }

    public static function canDelete(array $authUser, int $targetUserId): bool {
        if ($authUser['role'] === "Admin") return true;

        return $authUser['sub'] === $targetUserId;
    }
}
?>