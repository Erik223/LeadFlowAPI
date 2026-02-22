<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

class AuthController {
    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function login(Request $req, Response $res) {
        $data = $req->body;

        if (!isset($data['email'], $data['password'])) {
            $res->status(400);
            $res->json(["error" => "Invalid payload"]);
        }

        $token = $this->authService->attempt(
            $data['email'],
            $data['password']
        );

        if (!$token) {
            $res->status(401);
            $res->json(["error" => "Invalid credentials"]);
        }

        $res->status(200);
        $res->json(["token" => $token]);
    }
}
?>