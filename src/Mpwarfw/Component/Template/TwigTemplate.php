<?php

namespace Mpwarfw\Component\Template;

use Twig_Environment;
use Twig_Loader_Filesystem;

class TwigTemplate implements Template
{
    private $twig_loader;
    private $twig;
    private $view_path;

    public function __construct($view_path)
    {
       $this->view_path = $view_path;
    }

    public function render($template, $params = null ){

        $this->twig_loader     = new Twig_Loader_Filesystem($this->view_path);
        $this->twig       = new Twig_Environment( $this->twig_loader, array() );
         $template = $template.'.twig';
        if(!file_exists($this->view_path."/".$template)){
            throw new \Exception('El template ' . $template . ' no existe');
        }

        return $this->twig->render($template, $params );
    }
}