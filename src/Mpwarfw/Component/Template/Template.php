<?php

namespace Mpwarfw\Component\Template;

interface Template
{
    public function createView($template, $params = null );
}