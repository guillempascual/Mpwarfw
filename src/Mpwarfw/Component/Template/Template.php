<?php

namespace Mpwarfw\Component\Template;

interface Template
{
    public function render($template, $params = null );
}