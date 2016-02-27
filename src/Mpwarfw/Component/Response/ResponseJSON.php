<?php

namespace Mpwarfw\Component\Response;

class ResponseJSON implements Response
{
    private $response;

    public function __construct()
    {
    }

    public function setResponse($result)
    {
        $this->response = json_encode($result);
    }
    public function send()
    {
        header('Content-Type: application/json');
        echo $this->response;
    }
}
