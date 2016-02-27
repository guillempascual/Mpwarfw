<?php

namespace Mpwarfw\Component\Response;

class ResponseHTTP implements Response
{
    public function setResponse($result)
    {
        $this->response = <<<EOT
       <html>
       <head>
       </head>
       <body>
                $result
       </body>
       </html>
EOT;
    }

    public function send()
    {
        header('Content-Type: text/html; charset=UTF-8');
        echo $this->response;
    }
}