<?php
namespace app\core;

class Request {
    public string $method;
    public string $path;
    public array $headers;
    public array $body;
    public array $params = [];

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->headers = getallheaders() ?? [];
        $this->body = json_decode(file_get_contents("php://input"), true) ?? [];
    }
}
?>