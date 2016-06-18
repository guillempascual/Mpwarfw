<?php
namespace Mpwarfw\Component\Controller;
use Mpwarfw\Component\Container\Container;
abstract class ContainerController
{
    protected $container;
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}