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

    public function getPath()
    {
        $url_segments = explode("/", parse_url($this->server['PATH_INFO'],PHP_URL_PATH));

        if(count($url_segments) > 0){
            return $url_segments[ 1 ];
        }
        return 'index';
    }

    public function getParams()
    {
        $url_segments = explode("/", parse_url($this->server['PATH_INFO'],PHP_URL_PATH));

        $params = [];
        if(count($url_segments) > 2){
            for ($param_index = 2; $param_index < count($url_segments); $param_index++){
                $params[] = $url_segments[ $param_index ];
            }
        }
        return $params;
    }
}