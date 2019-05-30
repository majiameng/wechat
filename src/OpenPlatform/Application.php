<?php
namespace tinymeng\Wechat\OpenPlatform;

use tinymeng\Wechat\OpenPlatform\Server\Guard;

class Application{
    private $config;
    public $server;

    public function __construct($config)
    {
        $this->server = new Guard();
        $this->server->config = $config;
    }

    public function index(){

    }

    /**
     * Name: 获取用户授权页 URL
     * Author: Tinymeng <666@majiameng.com>
     */
    public function getPreAuthorizationUrl($callback_url){

    }

    /**
     * Name: 使用授权码换取接口调用凭据和授权信息
     * Author: Tinymeng <666@majiameng.com>
     */
    public function handleAuthorize(string $authCode = null){

    }

    /**
     * Name: 获取授权方的帐号基本信息
     * Author: Tinymeng <666@majiameng.com>
     * @param string $appId
     */
    public function getAuthorizer(string $appId){

    }
    /**
     * Name: 获取授权方的选项设置信息
     * Author: Tinymeng <666@majiameng.com>
     * @param string $appId
     */
    public function getAuthorizerOption(string $appId, string $name){

    }

    /**
     * Name: 设置授权方的选项信息

     * Author: Tinymeng <666@majiameng.com>
     * @param string $appId
     */
    public function setAuthorizerOption(string $appId, string $name, string $value){

    }

    /**
     * Name: 获取已授权的授权方列表
     * Author: Tinymeng <666@majiameng.com>
     * @param string $appId
     */
    public function getAuthorizers(int $offset = 0, int $count = 500){

    }


}
