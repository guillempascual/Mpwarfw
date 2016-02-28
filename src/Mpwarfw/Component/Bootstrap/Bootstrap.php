<?php
namespace Mpwarfw\Component\Bootstrap;

use Mpwarfw\Component\Container\Container;
use Mpwarfw\Component\Database\PDOConnection;
use Mpwarfw\Component\Request\Request;
use Mpwarfw\Component\Response\ResponseHTTP;
use Mpwarfw\Component\Response\ResponseJSON;
use Mpwarfw\Component\Routing\Router;
use Mpwarfw\Component\Template\SmartyTemplate;
use Mpwarfw\Component\Template\TwigTemplate;

class Bootstrap
{
    private $environment;

    public function __construct($environment)
    {
        date_default_timezone_set('Europe/Paris');
        $this->environment = $environment;
    }

    public function execute(Request $request){

        $container = new Container();
        $container['PDOConnection'] = new PDOConnection(HOST,DBNAME,USER,PASSWORD);
        $container['TwigTemplate'] = new TwigTemplate(VIEW_PATH);
        $container['SmartyTemplate'] = new SmartyTemplate(VIEW_PATH);
        $container['ResponseHTTP'] = new ResponseHTTP();
        $container['ResponseJSON'] = new ResponseJSON();
        $container['Router'] = new Router();

        $router = $container['Router'];
        $route = $router->getController($request);
        $request->setParams($route->params);

        $controller_class = new $route->controller();
        if(is_subclass_of($controller_class,'DefaultController')){
            $controller_class->setServicesContainer($container);
        }
        $controller_class->setServicesContainer($container);

        $response = call_user_func(array($controller_class, $route->action), $request);

        return $response;
    }
}
