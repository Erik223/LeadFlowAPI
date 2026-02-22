<?php
namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Core\Middleware;
use App\Core\JWT;

class AuthMiddleware implements Middleware {
    public function handle(Request $req, Response $res, callable $next): void {
        $headers = $req->headers;

        if (!isset($headers['Authorization'])) {
            $res->status(401);
            $res->json(["error" => "Unauthorized"]);
            return;
        }

        $token = str_replace("Bearer ", "", $headers['Authorization']);

        $payload = JWT::validate($token);

        if (!$payload) {
            $res->status(401);
            $res->json(["error" => "Invalid or expired token"]);
            return;
        }

        $req->user = $payload;
        $next();
    }
}
?>