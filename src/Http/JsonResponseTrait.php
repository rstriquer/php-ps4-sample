<?php


namespace Cms\Http;

use Exception;

trait JsonResponseTrait
{
    public function json($data, int $statusCode = 200): bool
    {
        header('Session-Id: ' . session_id());
        header('Content-Type: application/json;charset=utf-8');
        if (is_array($data)) {
            $body = '{"data": '.json_encode($data).'}';
        } else {
            $body = '{"data": {"response": '.json_encode($data).'}}';
        }
        if (json_last_error() === JSON_ERROR_NONE) {
            http_response_code($statusCode);
        } else {
            http_response_code(400);
            $body = '{"error_code": "001", "error_message": "Undefined Error"}';
        }
        echo $body;
        exit();
    }
}