<?php
namespace App\Core;

class Response {
    private int $status = 200;

    public function status(int $code): void {
        $this->status = $code;
        http_response_code($code);
    }

    public function json($data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
?>