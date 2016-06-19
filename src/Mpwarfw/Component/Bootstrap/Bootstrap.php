<?php
namespace Mpwarfw\Component\Bootstrap;

use Mpwarfw\Component\Container\Container;
use Mpwarfw\Component\Request\Request;
use Symfony\Component\Yaml\Parser;

class Bootstrap
{
    private $parser;
    private $environment;
    private $pathToServicesFile;
    private $container;

    public function __construct(Parser $parser, $pathToServicesFile, $environment)
    {
        date_default_timezone_set('Europe/Paris');
        $this->parser = $parser;
        $this->pathToServicesFile;
        $this->environment = $environment;
        $this->container = new Container($parser,$pathToServicesFile,$environment);

    }

    public function execute(Request $request){

        $router = $this->container->getService('router');
        $route                     = $router->retrieveRoute($request);
        $controller_to_instantiate = $route->getController();
        $action_to_execute         = $route->getAction();
        $params                    = implode(",",$request->getParams());
        $current_controller = new $controller_to_instantiate($this->container);

        return $current_controller->$action_to_execute($params);
    }
}
