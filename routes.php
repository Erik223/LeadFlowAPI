<?php

use App\Controllers\AuthController;
use App\Controllers\LeadController;
use App\Controllers\UserController;
use App\Core\Router;
use App\Middlewares\AuthMiddleware;

$user = new UserController();
$auth = new AuthController();
$lead = new LeadController();
$authMid = new AuthMiddleware();
$router = new Router();

$router->get("/users", fn($req, $res) => $user->index($req, $res), [$authMid]);
$router->get("/users/{id}", fn($req, $res) => $user->show($req, $res), [$authMid]);
$router->post("/register", fn($req, $res) => $user->store($req, $res));
$router->post("/login", fn($req, $res) => $auth->login($req, $res));
$router->put("/users/{id}", fn($req, $res) => $user->update($req, $res), [$authMid]);
$router->delete("/users/{id}", fn($req, $res) => $user->destroy($req, $res), [$authMid]);

$router->get("/leads", fn($req, $res) => $lead->index($req, $res), [$authMid]);
$router->get("/leads/{id}", fn($req, $res) => $lead->show($req, $res), [$authMid]);
$router->post("/leads", fn($req, $res) => $lead->store($req, $res), [$authMid]);
$router->put("/leads/{id}", fn($req, $res) => $lead->update($req, $res), [$authMid]);
$router->delete("/leads/{id}", fn($req, $res) => $lead->destroy($req, $res), [$authMid]);
?>