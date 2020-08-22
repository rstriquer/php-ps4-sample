<?php
/**
 * App controller.
 * 
 * @category   Controller
 * @package    App\Controller
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

namespace App\Controllers;

use App\Repositories\KnowledgeRepository;

class Controller
{
    protected $requestBody;

    public function __construct()
    {
        $data = file_get_contents('php://input');
        $this->requestBody = json_decode($data, true);
    }
}