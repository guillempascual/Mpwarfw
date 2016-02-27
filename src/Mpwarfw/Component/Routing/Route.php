<?php

namespace Mpwarfw\Component\Routing;

class Route
{
    public $path;
    public $controller;
    public $action;
    public $params;

    public function __construct($path,$controller,$action)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
    }
}