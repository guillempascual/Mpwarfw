<?php

namespace Mpwarfw\Component\Template;

use Smarty;

class SmartyTemplate implements Template
{

    private $smarty;
    private $view_path;

    public function __construct($view_path)
    {
        $this->view_path = $view_path;
    }

    public function createView($template, $params = null ){

        $this->smarty = new Smarty;
        $this->smarty->debugging = false;
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 120;
        $this->smarty->template_dir = $this->view_path;
        $this->smarty->config_dir = $this->view_path."/conf/";
        $this->smarty->compile_dir = $this->view_path."/cache/";

        $template = $template.'.tpl';
        if(!file_exists($this->view_path."/".$template)){
            throw new \Exception('El template ' . $template . ' no existe');
        }

        $this->smarty->assign($params);

        return $this->smarty->fetch($template);
    }

}