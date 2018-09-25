<?php

namespace Popo1h\PhaadminServer\RequestBuilder;

use Popo1h\PhaadminCore\Request;
use Popo1h\PhaadminServer\RequestBuilder;

class CommonRequestBuilder extends RequestBuilder
{
    public function buildRequest()
    {
        $request = new Request();

        $server = [];
        $get = $_GET;
        $post = $_POST;
        $files = $_FILES;

        if (isset($get['__action_name'])) {
            $server[Request::SERVER_NAME_ACTION_NAME] = $get['__action_name'];
            unset($get['__action_name']);
        } else {
            $server[Request::SERVER_NAME_ACTION_NAME] = null;
        }

        if (isset($get['__cate_name'])) {
            $server[Request::SERVER_NAME_CATE_NAME] = $get['__cate_name'];
            unset($get['__cate_name']);
        } else {
            $server[Request::SERVER_NAME_CATE_NAME] = null;
        }

        $request->setServer($server);
        $request->setGet($get);
        $request->setPost($post);
        $request->setFileByFiles($files);

        return $request;
    }
}
