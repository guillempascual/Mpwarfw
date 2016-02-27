<?php

namespace Mpwarfw\Component\Routing;


use Mpwarfw\Component\Request\Request;

class Router
{
    private $routeCollection;
    public function __construct()
    {
        $this->routeCollection  = RouteLoader::getRoutesFromYAML('../app/routes.yml');
    }

    public function getController(Request $request)
    {
        $route_parts = explode('/',$request->server['REQUEST_URI']);
        $path = $route_parts[1];

        $selected_route = $this->routeCollection->allRoutes[$path];
        $selected_route->params = $route_parts;
        return $selected_route;
    }
}