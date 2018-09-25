<?php

namespace Popo1h\PhaadminServer;

use Popo1h\PhaadminCore\Request;

abstract class ProviderServerInfoBuilder
{
    /**
     * @param Request $request
     * @return ProviderServerInfo
     */
    abstract public function getProviderServerInfo(Request $request);
}
