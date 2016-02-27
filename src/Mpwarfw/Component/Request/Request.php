<?php

namespace Mpwarfw\Component\Request;

class Request
{

    private $get;
    private $post;
    private $cookie;
    private $session;
    public $server;
    private $params;

    public function __construct($get, $post, $cookie, $session, $server)
    {
        $this->get = $get;
        $this->post = $post;
        $this->cookie = $cookie;
        $this->session = $session;
        $this->server = $server;
    }

    public static function create()
    {
        session_start();
        return new self($_GET,$_POST,$_COOKIE,$_SESSION, $_SERVER);
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}