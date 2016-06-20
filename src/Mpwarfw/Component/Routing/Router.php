<?php

namespace Mpwarfw\Component\Routing;
use Mpwarfw\Component\Request\Request;
use Mpwarfw\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Yaml\Parser;

class Router
{
    private $routes;

    public function __construct(Parser $parser, $pathToRoutesDefinitionFile)
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
        if(!isset($this->routes[$path]))
        {
            throw new RouteNotFoundException('Route not found');
        }
        return $this->routes[$path];
    }

    public function retrieveParams(Request $request)
    {
        return implode(",",$request->getParams());
    }
}