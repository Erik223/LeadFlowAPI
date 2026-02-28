<?php
namespace App\Services;

use App\Repositories\UserRepository;
use App\Core\JWT;

class AuthService {
    private UserRepository $repository;

    public function __construct() {
        $this->repository = new UserRepository();
    }

    public function attempt(string $email, string $password): ?string {
        $user = $this->repository->findByEmail($email);
        if (!$user) return null;

        if (!password_verify($password, $user->getPasswordHash())) return null;

        return JWT::generate([
            "sub" => $user->getId(),
            "email" => $user->getEmail(),
            "role" => $user->getRole()
        ]);
    }
}
?>