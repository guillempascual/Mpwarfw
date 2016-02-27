<?php

namespace Mpwarfw\Component\Routing;

class RouteCollection
{
    public $allRoutes= array();

    public function add(Route $route)
    {
        $route_parts = explode('/',$route->path);
        $path = $route_parts[1];

        $this->allRoutes[$path] = $route;
    }

}