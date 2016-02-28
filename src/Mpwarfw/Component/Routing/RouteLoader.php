<?php

namespace Mpwarfw\Component\Routing;

use Symfony\Component\Yaml\Parser;

class RouteLoader
{

    public static function getRoutesFromYAML($pathToYamlRoutesFile)
    {
        $routes = new RouteCollection();

        $yaml = new Parser();
        $all_parsed_routes = $yaml->parse(file_get_contents($pathToYamlRoutesFile));

        foreach ($all_parsed_routes as $route){
            $routes->add(new Route($route['path'], $route['controller'], $route['action']));
        }
        return $routes;
    }
}