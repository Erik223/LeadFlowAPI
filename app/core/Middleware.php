<?php
namespace app\core;

interface Middleware {
    public function handle(Request $req, Response $res, callable $next): void;
}
?>