<?php

namespace Mpwarfw\Component\Template;

class SmartyTemplate implements Template
{

    private $smarty;
    private $view_path;

    public function __construct($smarty,$view_path)
    {
        $this->smarty = $smarty;
        $this->view_path = $view_path;
        $this->smarty->debugging = false;
        $this->smarty->caching = true;
        $this->smarty->cache_lifetime = 120;
        $this->smarty->template_dir = $this->view_path;
        $this->smarty->config_dir = $this->view_path."/conf/";
        $this->smarty->compile_dir = $this->view_path."/cache/";
    }

    public function render($template_to_render, $params = null ){

        if(!file_exists($this->view_path."/".$template_to_render)){
            throw new \Exception('El template ' . $template_to_render . ' no existe');
        }

        $this->smarty->assign($params);

        return $this->smarty->fetch($template_to_render);
    }

}