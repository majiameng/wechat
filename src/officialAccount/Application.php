<?php
namespace tinymeng\Wechat\officialAccount;

use tinymeng\tools\HttpRequest;
use tinymeng\Wechat\Connector\Gateway;

class Application extends Gateway {
    /**
     * Author: TinyMeng <666@majiameng.com>
     * @var Guard
     */
    public $server;

    
    /** 开放平台access_token @var  */
    protected $access_token;

    public function __construct($config)
    {
        if (!$config) {
            throw new \Exception('传入的配置不能为空');
        }
        //默认参数
        $_config = [
            'app_id'        => '',
            'app_secret'    => '',
            'callback'      => '',
            'response_type' => 'code',
            'grant_type'    => 'authorization_code',
            'proxy'         => '',
            'state'         => '',
            'is_sandbox'    => false,//是否是沙箱环境
        ];
        $this->config    = array_merge($_config, $config);
    }

    /**
     * 设置 accessToken
     * @param $access_token
     * Author: TinyMeng <666@majiameng.com>
     * @return $this
     */
    public function setAccessToken($access_token){
        $this->access_token = $access_token;
        return $this;
    }

    /**
     * Name: 检测 accessToken 是否设置
     * Author: Tinymeng <666@majiameng.com>
     * @throws \Exception
     */
    private function issetAccessToken(){
        if(!$this->access_token){
            throw new \Exception('请设置access_token,使用 function setAccessToken()');
        }
    }

    /**
     * 根据ticket 获取 accessToken
     * 第三方平台access_token是第三方平台的下文中接口的调用凭据，也叫做令牌（access_token）。
     * 每个令牌是存在有效期（2小时）的，且令牌的调用不是无限制的，请第三方平台做好令牌的管理，在令牌快过期时（比如1小时50分）再进行刷新。
     * @param $ticket
     * Author: TinyMeng <666@majiameng.com>
     * @return mixed
     */
    public function getAccessToken($ticket){
//        $params = [
//            'component_appid'=>$this->config['app_id'],
//            'component_appsecret'=>$this->config['app_secret'],
//            'component_verify_ticket'=>$ticket,
//        ];
//        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
//        $result = HttpRequest::httpPost($url,json_encode($params));
//        return $result;
    }

    /**
     * Name: 自定义菜单创建接口
     * Author: Tinymeng <666@majiameng.com>
     * @param $params
     * @param $access_token
     * @return mixed
     * @internal param string $appId
     * 如返回: "errcode": 40119, "errmsg": "invalid use button type hint: [L1Jv508791891]",
     * 有可能是因为没有接口权限。
     */
    public function menuCreate($params){
        $this->issetAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
        return HttpRequest::httpPost($url,json_encode($params,JSON_UNESCAPED_UNICODE));
    }


}
