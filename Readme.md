## Action Server ##

    $actionServer = new \Popo1h\PhaadminServer\ActionServer();
    
    // request builder
    $actionServer->setRequestBuilder(new \Popo1h\PhaadminServer\RequestBuilder\CommonRequestBuilder());
    
    // provider server info builder
    $actionServer->setProviderServerBuilder(new class extends \Popo1h\PhaadminServer\ProviderServerInfoBuilder{
        // TODO Overrider getProviderServerInfo
    });
    
    // response handler
    $actionServer->setResponseHandler(new \Popo1h\PhaadminServer\ResponseHandler\CommonResponseHandler());
    
    $actionServer->listen();