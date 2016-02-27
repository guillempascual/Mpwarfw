<?php

namespace Mpwarfw\Component\Response;

interface Response
{
    public function setResponse($result);
    public function send();
}