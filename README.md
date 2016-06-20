Mpwarfw Framework
==============
**Author:** Guillem Pascual Aventín <[guillempascual on GitHub](https://github.com/guillempascual "guillempascual")>

Minimal PHP framework just meant to explore concepts on Frameworks Theory. It's fully functional.

There is a proof of concept app using this framework [Mpwarapp](https://github.com/guillempascual/mpwarapp "Mpwarapp")

Installation
-------------
**Mpwarfw** might be installed via composer.
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
 -  It can handle two different templating engines, **Twig** and **Smarty**.
```php
$twigTemplate = $this->container->getService('TwigTemplate');
$smartyTemplate = $this->container->getService('SmartyTemplate');
```
 - It supports **prod** and **dev** environments.
 - A **Translator** service
```php
$translator = $this->container->getService('Translator');
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
│   │    ├─ router.yml
│   │    ├─ services.yml
│   │    └─ services_dev.yml
│   └─ i18n/
│         ├─ es.yml
│         ├─ fr.yml
└─ public/
       └─ index.php (front controller)
       └─ index_dev.php (front controller for developers)
```

##Routing
The *retrieveRoute* method in the *Router* class finds out what the controller and action are regardless of its position in the URL. This way the system doen't depend so heavely on how the user sets up her *Virtual Hosts*.

This is so because it relies on the information in $_SERVER['PATH_INFO'] for parsing the route.

###routes.json format
```yml
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
    
Translator:
  class: Mpwarfw\Component\i18n\Translator
  arguments:
      - "@yml_parser"
  public: true
```
