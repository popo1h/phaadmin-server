<?php

namespace Popo1h\PhaadminServer;

use Popo1h\PhaadminCore\Request;
use Popo1h\PhaadminCore\Response;
use Popo1h\PhaadminServer\Exception\ProviderServerInfoBuilderNotSetException;
use Popo1h\PhaadminServer\Exception\ProviderServerNotFoundException;
use Popo1h\PhaadminServer\Exception\RequestBuildException;
use Popo1h\PhaadminServer\Exception\RequestErrorException;
use Popo1h\PhaadminServer\Exception\ResponseErrorException;
use Popo1h\PhaadminServer\Interceptor\BeforeActionInterceptor;
use Popo1h\PhaadminServer\RequestBuilder\CommonRequestBuilder;
use Popo1h\PhaadminServer\ResponseHandler\CommonResponseHandler;
use Popo1h\Support\Objects\StringPack;

class ActionServer
{
    /**
     * @var RequestBuilder
     */
    private $requestBuilder;
    /**
     * @var ProviderServerInfoBuilder
     */
    private $providerServerInfoBuilder;
    /**
     * @var ResponseHandler
     */
    private $responseHandler;
    /**
     * @var BeforeActionInterceptor[]
     */
    private $beforeActionInterceptors = [];

    /**
     * @param RequestBuilder $requestBuilder
     */
    public function setRequestBuilder(RequestBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;
    }

    /**
     * @return CommonRequestBuilder
     */
    private function getRequestBuilder()
    {
        if (!isset($this->requestBuilder)) {
            $this->requestBuilder = new CommonRequestBuilder();
        }

        return $this->requestBuilder;
    }

    /**
     * @param ProviderServerInfoBuilder $providerServerInfoBuilder
     */
    public function setProviderServerBuilder(ProviderServerInfoBuilder $providerServerInfoBuilder)
    {
        $this->providerServerInfoBuilder = $providerServerInfoBuilder;
    }

    /**
     * @return ProviderServerInfoBuilder
     * @throws ProviderServerInfoBuilderNotSetException
     */
    private function getProviderServerInfoBuilder()
    {
        if (!isset($this->providerServerInfoBuilder)) {
            throw new ProviderServerInfoBuilderNotSetException();
        }

        return $this->providerServerInfoBuilder;
    }

    /**
     * @param ResponseHandler $responseHandler
     */
    public function setResponseHandler(ResponseHandler $responseHandler)
    {
        $this->responseHandler = $responseHandler;
    }


    /**
     * @return ResponseHandler
     */
    private function getResponseHandler()
    {
        if (!isset($this->responseHandler)) {
            $this->responseHandler = new CommonResponseHandler();
        }

        return $this->responseHandler;
    }

    /**
     * @param BeforeActionInterceptor $beforeActionInterceptor
     * @param bool $front append on front
     */
    public function appendBeforeActionInterceptor(BeforeActionInterceptor $beforeActionInterceptor, $front = false)
    {
        if ($front) {
            array_unshift($this->beforeActionInterceptors, $beforeActionInterceptor);
        } else {
            array_push($this->beforeActionInterceptors, $beforeActionInterceptor);
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ProviderServerInfoBuilderNotSetException
     * @throws RequestErrorException
     * @throws ProviderServerNotFoundException
     * @throws ResponseErrorException
     */
    public function request(Request $request)
    {
        $beforeActionInterceptorIterator = new \ArrayIterator($this->beforeActionInterceptors);
        $funcRunBeforeActionInterceptor = function ($request) use ($beforeActionInterceptorIterator, &$funcRunBeforeActionInterceptor) {
            if ($beforeActionInterceptorIterator->valid()) {
                $nextBeforeActionInterceptor = $beforeActionInterceptorIterator->current();
                $beforeActionInterceptorIterator->next();
                $request = $nextBeforeActionInterceptor->intercept($request, $funcRunBeforeActionInterceptor);
            }
            if (!$request instanceof Request) {
                if ($request instanceof Response) {
                    return $request;
                }
                throw new RequestErrorException($request);
            }
            return $request;
        };

        $request = $funcRunBeforeActionInterceptor($request);
        if ($request instanceof Response) {
            return $request;
        }

        $providerServerInfo = $this->getProviderServerInfoBuilder()->getProviderServerInfo($request);
        if (!$providerServerInfo instanceof ProviderServerInfo) {
            throw new ProviderServerNotFoundException();
        }

        $responseStr = $providerServerInfo->getNet()->request(
            $providerServerInfo->getServerUrl(),
            StringPack::pack($request, true),
            $providerServerInfo->getHostIps()
        );

        try {
            $response = StringPack::unpack($responseStr);
        } catch (\Exception $e) {
            throw new ResponseErrorException($responseStr);
        }

        if (!$response instanceof Response) {
            throw new ResponseErrorException($responseStr);
        }

        return $response;
    }

    /**
     * @return mixed
     * @throws ProviderServerInfoBuilderNotSetException
     * @throws RequestBuildException
     * @throws RequestErrorException
     * @throws ProviderServerNotFoundException
     * @throws ResponseErrorException
     */
    public function listen()
    {
        $request = $this->getRequestBuilder()->buildRequest();
        if (!$request instanceof Request) {
            throw new RequestBuildException();
        }

        $response = $this->request($request);

        return $this->getResponseHandler()->handler($response);
    }
}
