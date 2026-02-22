<?php
namespace App\Core;

interface Middleware {
    public function handle(Request $req, Response $res, callable $next): void;
}
?>