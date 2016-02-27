<?php

namespace Mpwarfw\Component\Routing;

use Symfony\Component\Yaml\Parser;

class RouteLoader
{

    public static function getRoutesFromYAML($pathToYamlRoutesFile)
    {
        $routes = new RouteCollection();

        // Leer el YAML -> APP
        $yaml = new Parser();
        $all_parsed_routes = $yaml->parse(file_get_contents($pathToYamlRoutesFile));

        foreach ($all_parsed_routes as $route){
            // URL: vinos -> YAML: vinos [ProductClass + showProduct]
            //$routes->add(new Route('/foo', 'controller', 'method'));
            $routes->add(new Route($route['path'], $route['controller'], $route['action']));
        }
        return $routes;
    }
}