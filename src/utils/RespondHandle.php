<?php

namespace App\utils;

class RespondHandle {

    public static function respond($data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $statusCode,
            'data' => $data
        ]);
        exit;
    }

    public function respondError($message, int $statusCode = 400)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $statusCode,
            'error' => $message
        ]);
        exit;
    }
}

