<?php

namespace Mpwaffw\Component\Response\Exception;
use Exception;
use InvalidArgumentException;

class NotValidRedirectionStatusCodeException extends InvalidArgumentException
{
    public function __construct($message, $code, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public static function throwDefault($redirectCode)
    {
        $message = "The code $redirectCode is not a valid Redirect Code";
        throw new self($message, 1);
    }
}
