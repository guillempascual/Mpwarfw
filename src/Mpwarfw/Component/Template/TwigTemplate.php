<?php

namespace Mpwarfw\Component\Template;

class TwigTemplate implements Template
{

    private $twig_environment;
    private $variables_to_use;

    public function __construct($twig_environment)
    {
        $this->twig_environment = $twig_environment;
        $this->variables_to_use = [];
    }

    public function render($template_to_render, $params = [])

    {
        return $this->twig_environment->render($template_to_render, $params);
    }
}