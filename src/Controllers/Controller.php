<?php


namespace Cms\Controllers;


class Controller
{
    protected $requestBody;

    public function __construct()
    {
        $data = file_get_contents('php://input');
        $this->requestBody = json_decode($data, true);
    }
}