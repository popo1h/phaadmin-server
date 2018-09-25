<?php

namespace Popo1h\PhaadminServer\Interceptor;

use Popo1h\PhaadminCore\Request;
use Popo1h\PhaadminCore\Response;

abstract class BeforeActionInterceptor
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return Request|Response
     */
    abstract public function intercept(Request $request, \Closure $next);
}