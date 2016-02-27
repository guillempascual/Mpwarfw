<?php
namespace Mpwarfw\Component\Bootstrap;

use Mpwarfw\Component\Request\Request;
use Mpwarfw\Component\Routing\RouteCollection;
use Mpwarfw\Component\Routing\RouteLoader;
use Mpwarfw\Component\Routing\Router;


class Bootstrap
{
    private $environment;

    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    public function __invoke($queryString)
    {
        $router = new Router($queryString);


    }

    public function execute(Request $request){
        $router = new Router();
        $route = $router->getController($request);
        $request->setParams($route->params);

        $controller_class = new $route->controller();
        $response = call_user_func(array($controller_class, $route->action), $request);

        return $response;
    }
}
