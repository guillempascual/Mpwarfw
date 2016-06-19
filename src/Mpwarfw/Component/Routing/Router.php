<?php

namespace Mpwarfw\Component\Routing;
use Mpwarfw\Component\Request\Request;

class Router
{
    private $routes;

    public function __construct($parser, $pathToRoutesDefinitionFile)
    {
        $defined_routes = $parser->parse(file_get_contents($pathToRoutesDefinitionFile));

        foreach ($defined_routes as $route){
            $this->addRoute(new Route($route['path'], $route['controller'], $route['action']));
        }
    }

    public function addRoute(Route $route)
    {
        $route_parts = explode('/',$route->getPath());
        $path = $route_parts[1];
        $this->routes[$path] = $route;
    }

    public function retrieveRoute(Request $request)
    {
        $path = $request->getPath();
        return $this->routes[$path];
    }
}