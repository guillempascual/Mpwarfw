<?php

namespace Mpwarfw\Component\Container;

use Symfony\Component\Yaml\Parser;

class ContainerLoader
{
    public static function loadContainer($pathToYamlContainerServicesFile)
    {
        $yaml = new Parser();
        $all_parsed_services= $yaml->parse(file_get_contents($pathToYamlContainerServicesFile));

        return $all_parsed_services;
    }
}