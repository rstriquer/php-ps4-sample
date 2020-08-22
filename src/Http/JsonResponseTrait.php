<?php
/**
 * Apps Json Response Trait.
 * 
 * @category   Http
 * @package    App\Http
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

namespace App\Http;

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
    public function jsonError(\Exception $e): void
    {
        if (getenv('APP_ENV')=='development' || getenv('APP_DEBUG') === 'true') {
            $this->json(
                'Error: ' . $e->getMessage() . ' (line:' . $e->getLine() . ')',
                401
            );
        } else {
            $this->json(
                'Error: Algum erro técnico aconteceu. Favor tentar novamente mais tarde ou notificar o administrador de sistemas',
                401
            );
        }
    }
}