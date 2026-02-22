<?php
namespace App\Core;

class Router {
    private array $routes = [];

    public function get($path, $handler, $mid = []) { $this->add('GET', $path, $handler, $mid); }
    public function post($path, $handler, $mid = []) { $this->add('POST', $path, $handler, $mid); }
    public function put($path, $handler, $mid = []) { $this->add('PUT', $path, $handler, $mid); }
    public function delete($path, $handler, $mid = []) { $this->add('DELETE', $path, $handler, $mid); }

    private function add(string $method, string $path, callable $handler, array $middlewares = []) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'regex' => $this->compilePath($path),
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    private function compilePath(string $path): string {
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path);
        return "#^$pattern$#";
    }

    public function run(Request $req, Response $res) {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $req->method) continue;

            if (preg_match($route['regex'], $req->path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $req->params = $params;

                $this->executeQueue($route['middlewares'], $route['handler'], $req, $res);
                return;
            }
        }

        $res->status(404);
        $res->json(["error" => "Not found"]);
    }

    private function executeQueue(array $middlewares, callable $handler, Request $req, Response $res) {
        $stack = $middlewares;
        $stack[] = $handler;

        $this->runNext($stack, $req, $res);
    }

    private function runNext(array &$stack, Request $req, Response $res) {
        $actualCall = array_shift($stack);

        if (!$actualCall) return;

        $next = fn() => $this->runNext($stack, $req, $res);

        if ($actualCall instanceof Middleware) {
            $actualCall->handle($req, $res, $next);
        }
        else {
            $actualCall($req, $res);
        }
    }
}
?>