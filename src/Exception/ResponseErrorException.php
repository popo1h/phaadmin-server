<?php

namespace Popo1h\PhaadminServer\Exception;

use Popo1h\PhaadminCore\PhaadminException;

class ResponseErrorException extends PhaadminException
{
    /**
     * @var string
     */
    private $responseStr;

    /**
     * ResopnseErrorException constructor.
     * @param $responseStr
     */
    public function __construct($responseStr)
    {
        $this->responseStr = $responseStr;
    }

    /**
     * @return string
     */
    public function getResponseStr()
    {
        return $this->responseStr;
    }
}