<?php

namespace Popo1h\PhaadminServer\ResponseHandler;

use Popo1h\PhaadminCore\Response;
use Popo1h\PhaadminServer\ResponseHandler;

class CommonResponseHandler extends ResponseHandler
{
    public function handler(Response $response)
    {
        $responseOutput = $response->output();

        $headers = $responseOutput->getHeaders();
        foreach ($headers as $headerName => $value) {
            header($headerName . ':' . $value);
        }

        http_response_code($responseOutput->getStatusCode());

        echo $responseOutput->getContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }
}
