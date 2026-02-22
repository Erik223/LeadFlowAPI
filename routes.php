<?php
use App\Core\Router;
use App\Controllers\UserController;
use App\Middlewares\AuthMiddleware;

$user = new UserController();
$auth = new AuthMiddleware();
$router = new Router();

$router->get("/users", fn($req, $res) => $user->index($req, $res));
$router->get("/users/{id}", fn($req, $res) => $user->show($req, $res));
$router->post("/users", fn($req, $res) => $user->store($req, $res));
$router->put("/users/{id}", fn($req, $res) => $user->update($req, $res));
$router->delete("/users/{id}", fn($req, $res) => $user->destroy($req, $res));
?>