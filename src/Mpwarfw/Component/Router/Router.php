<?php
use Symfony\Component\Yaml\Parser;

class Router
{
    // Leer el YAML -> APP

    $yaml = new Parser();

    $value = $yaml->parse(file_get_contents('/path/to/file.yml'));
    // URL: vinos -> YAML: vinos [ProductClass + showPoroduct]
    // return
}