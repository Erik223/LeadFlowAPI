<?php
require __DIR__ . "/../vendor/autoload.php";

use App\Core\Env;
use App\Core\Request;
use App\Core\Response;

Env::load(__DIR__ . "/../.env");
$req = new Request();
$res = new Response();

require __DIR__ . "/../routes.php";

try {
    $router->run($req, $res);
}
catch(Exception $error) {
    $res->status(500);
    $res->json(["error" => $error->getMessage()]);
}