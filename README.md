Mpwarfw Framework
==============
**Author:** Guillem Pascual Aventín <[guillempascual on GitHub](https://github.com/guillempascual "guillempascual")>

Minimal PHP framework just meant to explore concepts on Frameworks Theory. It's fully functional.

There is a proof of concept app using this framework [Mpwarapp](https://github.com/guillempascual/mpwarapp "Mpwarapp")

Installation
-------------
**Mpwarfw** might be installed via composer, so add to your composer.json the following code.
This is how your *composer.json* might look like:

```json
{
    "name": "mpwarapp-standard-edition",
    "description": "Standard APP using MPWAR Framework",
    "license": "Apache-2.0",
    "keywords": [],
    "type": "project",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/guillempascual/Mpwarfw"
        }
    ],
    "require": {
        "php": ">=5.3.2",
        "guillempascual/Mpwarfw": "master"
    },
    "minimum-stability": "dev",
    "autoload": {
        "psr-4": {
            "Mpwarapp\\": "src/"
        }
    }
}
```

Framework Features
------------------------

**Mpwarfw** provides a minimal set of features just meant to test concepts on Frameworks Theory.

- It provides an abstract **ContainerController** class that can be extended by your controllers so that they'll have inner access to the services container, à la *Symfony*.
This is how a controller in your app might look like:

```php
<?php

namespace Mpwarapp\Home\Controller;

use Mpwarfw\Component\Controller\ContainerController;

class Customer extends ContainerController 
{
    public function showCustomerListJSON()
    {
        $pdoConnection = $this->container->getService('PDOConnection');
        $all_customers = $pdoConnection->execute("SELECT * FROM customer", array());
        $response = $this->container->getService('ResponseJSON');
        $response->setResponse($all_customers);
        return $response;
    }
```
 -  It can manage two different templating engines, **Twig** and **Smarty**.
```php
$twigTemplate = $this->container->getService('TwigTemplate');
$smartyTemplate = $this->container->getService('SmartyTemplate');
```
 - It supports **prod** and **dev** environments.
 - A **Translator** service
```php
$translator = $this->container->getService('translator');
```
 - The controller in your app can have access to the **Request** object that contains all information relative to the globals  \$_GET, \$_POST. \$_SERVER, \$_SESSION, \$_COOKIE and to the Route information. To gain access to it is enough to add to your controller method the param *Request $request*
```php
public function myControllerAction(Request $request) {
```
 - It provides two type of responses, **ResponseHTTP** and **ResponseJSON**
 - The **PDORepository** is a simpple interface to access your database.
```php
$PDOConnection = $this->container->getService('PDOContainer');
```

Framework Requirements
------------------------

Each application using this framework should have the following directory structure.
```
├─ app/
│   └─ confs/
│         ├─ general.yml
│         ├─ routing.yml
│         ├─ services.yml (optional)
│         ├─ services_prod.yml (optional)
│         └─ services_dev.yml (optional)
└─ public/
       └─ index.php (front controller)
       └─ index_dev.php (front controller for developers)
```

##Routing

**Default Routing Service**
```yml
router:
  class: \Night\Component\Routing\Routing
  arguments:
    - @yaml-parser
```
###routes.json format
```yml
routeWithoutArguments:
  route: /myroute
  path:
    classname: MyRouteWithoutArgumentsController
    callablemethod: myRouteAction
routeWithArguments:
  route: /myroute/{arg1}/{arg2}
  path:
    classname: MyRouteWithArgumentsController
    callablemethod: myRouteAction
notfound:
  route:
  path:
    classname: NotFoundController
    callablemethod: notFoundAction
- {path: /route-a, controller: controllerA, action: actionWithNoParams}
- {path: /route-b/1/2, controller: controllerB, action: actionWithParams}
```

###routes.json sample
```json
- {path: /customer-list-json, controller: \Mpwarapp\Home\Controller\Customer, action: showCustomerListJSON}
- {path: /customer-list-smarty, controller: \Mpwarapp\Home\Controller\Customer, action: showCustomerListSmarty}
- {path: /customer-list-twig, controller: \Mpwarapp\Home\Controller\Customer, action: showCustomerListTwig}
- {path: /customer-show, controller: \Mpwarapp\Home\Controller\Customer, action: show}
```

###Front Controller to be used in index.php in the application end
```php
<?php
use Mpwarfw\Component\Bootstrap\Bootstrap;
use Mpwarfw\Component\Request\Request;

require_once  __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/../app/constants.php';


$parser = new \Symfony\Component\Yaml\Parser();
$bootstrap = new Bootstrap($parser,APP_PATH,'PROD');

$request = Request::create();
$response = $bootstrap->execute($request);
$response->send();
```

### Services Container

The container has access to a set of services already defined.
```yml
router:
    class: Mpwarfw\Component\Routing\Router
    arguments:
      - "@yml_parser"
      - "/var/www/framework16/Mpwarapp/app/routes.yml"
    public: true

yml_parser:
    class: Symfony\Component\Yaml\Parser
    public: true

SmartyTemplate:
    class: Mpwarfw\Component\Template\SmartyTemplate
    arguments:
      - "@Smarty"
      - "/var/www/framework16/Mpwarapp/src/home/view"
    public: true

Smarty:
    class: Smarty
    public: false

TwigTemplate:
    class: Mpwarfw\Component\Template\TwigTemplate
    arguments:
      - "@TwigEnvironment"
    public: true

TwigEnvironment:
    class: Twig_Environment
    arguments:
      - "@TwigLoader"
      - "['cache' => '/var/www/html/framework16/Mpwarapp/src/home/cache', 'debug' => 'false']"
    public: false
    tags:
      - example
      - twig

TwigLoader:
    class: Twig_Loader_Filesystem
    arguments:
      - "/var/www/framework16/Mpwarapp/src/Home/View"
    public: false

PDOConnection:
    class: Mpwarfw\Component\Database\PDOConnection
    arguments:
      - "localhost"
      - "mpwar_frameworks"
      - "root"
      - "vagrantpass"
    public: true

ResponseHTTP:
    class: Mpwarfw\Component\Response\ResponseHTTP
    public:   true

ResponseJSON:
    class: Mpwarfw\Component\Response\ResponseJSON
    public: true
```


