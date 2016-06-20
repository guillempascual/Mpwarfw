<?php

namespace Mpwarfw\Component\Response;

interface Response
{
    public function setContent($html);
    public function send();
}