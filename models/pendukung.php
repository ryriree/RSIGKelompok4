<?php
require_once __DIR__ . '/../database/database.php';

class Respon
{
    public static function kirim($tampilan, $data = [])
    {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'tampilan' => $tampilan,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
    }
}