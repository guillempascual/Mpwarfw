<?php

namespace Mpwarfw\Component\Template;

use Smarty;

class SmartyTemplate implements Template
{

    private $smarty;

    public function createView($template, $params = null ){

        $this->smarty = new Smarty;
        //$this->smarty->force_compile = true;
        $this->smarty->debugging = true;
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 120;
        $this->smarty->template_dir = self::VIEW_PATH;
        $this->smarty->config_dir = self::APP_PATH."/conf";
        $this->smarty->compile_dir = self::APP_PATH."/cache";

        $template = $template.'.twig';
        if(!file_exists($template)){
            throw new \Exception('El template ' . $template . ' no existe');
        }

        if(isset($params)){
            foreach($params as $key => $value){
                $$key = $value;
            }
        }

        $content = '';
        include __DIR__ . '/view.php';

        return $content;
    }

}