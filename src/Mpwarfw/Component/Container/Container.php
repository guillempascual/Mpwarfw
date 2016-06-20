<?php

namespace Mpwarfw\Component\Container;
use Mpwarfw\Component\Container\Exception\ServiceNotOpenToPublicException;
use Mpwarfw\Component\Container\Exception\ServiceNotFoundException;
use ReflectionClass;
use Symfony\Component\Yaml\Parser;
class Container
{
    private $container;
    private $service_store;
    private $service_settings;

    public function __construct( Parser $yml_parser, $path_to_services_config_file, $current_environment = 'DEV')
    {
        $this->container     = [];
        $this->service_store = [];
        $this->service_settings = [];
        $production_service_definitions = $yml_parser->parse(file_get_contents($path_to_services_config_file . '/services.yml'));
        foreach ($production_service_definitions as $service => $content)
        {
            if (array_key_exists($service, $this->container))
            {
                continue;
            }
            $this->container[ $service ] = $content;
            $this->service_settings[$service]['public'] = $content['public'];
            if(isset($content['tags']))
            {
                foreach($content['tags'] as $tag)
                {
                    $this->service_settings[$service]['tags'][] = $tag;
                }
            }
        }

        if($current_environment === 'DEV')
        {
            $development_service_definitions = $yml_parser->parse(file_get_contents($path_to_services_config_file . '/services_dev.yml'));
            foreach($development_service_definitions as $service => $content)
            {
                if (array_key_exists($service, $this->container))
                {
                    continue;
                }
                $this->container[ $service ] = $content;
                $this->service_settings[$service]['public'] = $content['public'];
                if(isset($content['tags']))
                {
                    foreach($content['tags'] as $tag)
                    {
                        $this->service_settings[$service]['tags'][] = $tag;
                    }
                }
            }
        }
    }

    public function getService($service_name)
    {
        if (!array_key_exists($service_name, $this->container))
        {
            throw new ServiceNotFoundException('Service not found: ' . $service_name);
        }
        $this->service_store[ $service_name ] = $this->createService($this->container[ $service_name ]
        );
        if(!$this->service_settings[$service_name]['public'])
        {
            throw new ServiceNotOpenToPublicException('Service not open to public access.');
        }

        return $this->service_store[ $service_name ];
    }

    private function createService($service_schema)
    {

        if (isset( $service_schema['arguments'] ))
        {
            $services_arguments = [];
            foreach ($service_schema['arguments'] as $argument)
            {
                $first_character = substr($argument, 0, 1);
                if ('@' === $first_character)
                {
                    $service_to_ask       = str_replace("@", "", $argument);
                    $services_arguments[] = $this->createService($this->container[ $service_to_ask ]);
                }
                else
                {
                    $first_character = substr($argument, 0, 1);
                    if ('[' === $first_character)
                    {
                        $all_arguments = str_replace("[", "", $argument);
                        $all_arguments = str_replace("]", "", $all_arguments);
                        $all_arguments = str_replace("'", "", $all_arguments);
                        $all_arguments = str_replace(" ", "", $all_arguments);
                        $all_arguments = explode(",", $all_arguments);
                        $argument            = [];
                        foreach ($all_arguments as $next_argument)
                        {
                            $array_with_key_value = explode("=>", $next_argument);
                            $argument[ $array_with_key_value[0] ] = $array_with_key_value[1];
                        }
                    }
                    $services_arguments[] = $argument;
                }
            }
            $reflector = new ReflectionClass($service_schema['class']);
            return $reflector->newInstanceArgs($services_arguments);
        }
        return new $service_schema['class']();
    }
}
