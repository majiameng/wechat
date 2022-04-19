<?php
namespace tinymeng\Wechat\openPlatform;

use tinymeng\tools\HttpRequest;
use tinymeng\Wechat\openPlatform\Server\Guard;

class Application{
    /**
     * Author: TinyMeng <666@majiameng.com>
     * @var Guard
     */
    public $server;

    /**
     * 开放平台的配置
     * @var
     */
    private $config;
    
    /** 开放平台access_token @var  */
    protected $component_access_token;

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

        $this->server = new Guard();
        $this->server->config = $this->config;
    }

    /**
     * 设置 accessToken
     * @param $access_token
     * Author: TinyMeng <666@majiameng.com>
     * @return $this
     */
    public function setAccessToken($access_token){
        $this->component_access_token = $access_token;
        return $this;
    }

    /**
     * Name: 检测 accessToken 是否设置
     * Author: Tinymeng <666@majiameng.com>
     * @throws \Exception
     */
    private function issetAccessToken(){
        if(!$this->component_access_token){
            throw new \Exception('请设置component_access_token,使用 function setAccessToken()');
        }
    }

    /**
     * 根据ticket 获取 accessToken
     * 第三方平台component_access_token是第三方平台的下文中接口的调用凭据，也叫做令牌（component_access_token）。
     * 每个令牌是存在有效期（2小时）的，且令牌的调用不是无限制的，请第三方平台做好令牌的管理，在令牌快过期时（比如1小时50分）再进行刷新。
     * @param $ticket
     * Author: TinyMeng <666@majiameng.com>
     * @return mixed
     */
    public function getAccessToken($ticket){
        $params = [
            'component_appid'=>$this->config['app_id'],
            'component_appsecret'=>$this->config['app_secret'],
            'component_verify_ticket'=>$ticket,
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
        $result = HttpRequest::httpPost($url,json_encode($params));
        return $result;
    }

    /**
     * Name: 获取预授权码
     * Author: Tinymeng <666@majiameng.com>
     */
    public function createPreauthcode(){
        $this->issetAccessToken();//检测 accessToken 是否设置
        $params = [
            'component_appid'=>$this->config['app_id'],
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='.$this->component_access_token;
        return HttpRequest::httpPost($url,json_encode($params));
    }

    /**
     * Name: 获取用户授权页 URL
     * Author: Tinymeng <666@majiameng.com>
     * @param $redirect_uri
     * @param int $auth_type 要授权的帐号类型， 1则商户扫码后，手机端仅展示公众号、2表示仅展示小程序，3表示公众号和小程序都展示。如果为未制定，则默认小程序和公众号都展示。第三方平台开发者可以使用本字段来控制授权的帐号类型。
     * @param $ize_appid
     * 注：auth_type、biz_appid两个字段互斥。
     * @return string
     */
    public function getPreAuthorizationUrl($redirect_uri,$is_wx_mobile=false,$auth_type=3,$ize_appid=''){
        $preauthcode = $this->createPreauthcode();
        $preauthcode = json_decode($preauthcode,true);

        $params = [
            'component_appid'=>$this->config['app_id'],
            'pre_auth_code'=>$preauthcode['pre_auth_code'],
            'redirect_uri'=>$redirect_uri,
            'auth_type'=>$auth_type,
            'biz_appid'=>$ize_appid,
        ];
        if($is_wx_mobile === false){
            //授权注册页面扫码授权
            $url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage';
        }else{
            //移动端链接快速授权(必须在微信浏览器)
            $params['action'] = 'bindcomponent';
            $params['no_scan'] = '1';
            $url = 'https://mp.weixin.qq.com/safe/bindcomponent';
        }
        $url = $url . '?' . http_build_query($params);
        return $url;
    }

    /**
     * Name: 使用授权码换取接口调用凭据和授权信息
     * Author: Tinymeng <666@majiameng.com>
     */
    public function handleAuthorize( $authorization_code){
        $this->issetAccessToken();//检测 accessToken 是否设置
        $params = [
            'component_appid'=>$this->config['app_id'],
            'authorization_code'=>$authorization_code,
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token='.$this->component_access_token;
        return HttpRequest::httpPost($url,json_encode($params));
    }

    /**
     * Name: 获取授权方的帐号基本信息
     * Author: Tinymeng <666@majiameng.com>
     * @param string $appId
     */
    public function getAuthorizer( $authorizer_appid){
        $this->issetAccessToken();//检测 accessToken 是否设置
        $params = [
            'component_appid'=>$this->config['app_id'],
            'authorizer_appid'=>$authorizer_appid,
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token='.$this->component_access_token;
        return HttpRequest::httpPost($url,json_encode($params));
    }

    /**
     * Name: 获取（刷新）授权公众号或小程序的接口调用凭据（令牌）
     * @param $authorizer_appid
     * @param $authorizer_refresh_token
     * Author: TinyMeng <666@majiameng.com>
     * @return mixed
     */
    public function getAuthorizerToken( $authorizer_appid,$authorizer_refresh_token){
        $this->issetAccessToken();//检测 accessToken 是否设置
        $params = [
            'component_appid'=>$this->config['app_id'],
            'authorizer_appid'=>$authorizer_appid,
            'authorizer_refresh_token'=>$authorizer_refresh_token,
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token='.$this->component_access_token;
        return HttpRequest::httpPost($url,json_encode($params));
    }

    /**
     * Name: 获取授权方的选项设置信息
     * Author: Tinymeng <666@majiameng.com>
     * @param string $appId
     */
    public function getAuthorizerOption( $appId,  $name){

    }

    /**
     * Name: 设置授权方的选项信息

     * Author: Tinymeng <666@majiameng.com>
     * @param string $appId
     */
    public function setAuthorizerOption( $appId,  $name,  $value){

    }

    /**
     * Name: 获取已授权的授权方列表
     * Author: Tinymeng <666@majiameng.com>
     * @param string $appId
     */
    public function getAuthorizers($offset = 0, $count = 500){

    }


}
