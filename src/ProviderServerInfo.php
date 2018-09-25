<?php

namespace Popo1h\PhaadminServer;

use Popo1h\PhaadminCore\Net;

class ProviderServerInfo
{
    /**
     * @var Net
     */
    private $net;
    /**
     * @var string
     */
    private $serverUrl;
    /**
     * @var array
     */
    private $hostIps;

    /**
     * ProviderServerInfo constructor.
     * @param Net $net
     * @param string $serverUrl
     * @param array $hostIps
     */
    public function __construct(Net $net, $serverUrl, $hostIps = [])
    {
        $this->net = $net;
        $this->serverUrl = $serverUrl;
        $this->hostIps = $hostIps;
    }

    /**
     * @return Net
     */
    public function getNet()
    {
        return $this->net;
    }

    /**
     * @return string
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * @return array
     */
    public function getHostIps()
    {
        return $this->hostIps;
    }
}
