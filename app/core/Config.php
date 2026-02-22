<?php
namespace app\core;

class Config {
    public static function db(): array {
        return [
            'host' => Env::get('DB_HOST'),
            'port' => Env::get('DB_PORT'),
            'name' => Env::get('DB_NAME'),
            'user' => Env::get('DB_USER'),
            'pass' => Env::get('DB_PASS'),
            'charset' => Env::get('DB_CHARSET', 'utf8mb4')
        ];
    }
}
?>