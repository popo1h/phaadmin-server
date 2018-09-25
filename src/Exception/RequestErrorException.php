<?php

namespace Popo1h\PhaadminServer\Exception;

use Popo1h\PhaadminCore\PhaadminException;

class RequestErrorException extends PhaadminException
{
    /**
     * @var mixed
     */
    private $requestData;

    /**
     * RequestErrorException constructor.
     * @param mixed $requestData
     */
    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * @return mixed
     */
    public function getRequestData()
    {
        return $this->requestData;
    }
}