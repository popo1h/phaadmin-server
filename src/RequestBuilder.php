<?php

namespace Popo1h\PhaadminServer;

use Popo1h\PhaadminCore\Request;

abstract class RequestBuilder
{
    /**
     * @return Request
     */
    abstract public function buildRequest();
}
