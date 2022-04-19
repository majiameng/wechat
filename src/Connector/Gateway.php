<?php
namespace tinymeng\Wechat\Connector;

/**
 * 所有第三方必须继承的抽象类
 */
abstract class Gateway implements GatewayInterface
{
    /**
     * 配置参数
     * @var array
     */
    protected $config;

    /**
     * 是否验证回跳地址中的state参数
     * @var boolean
     */
    protected $checkState = false;

    /**
     * Gateway constructor.
     * @param null $config
     * @throws \Exception
     */
    public function __construct($config = null)
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
        $this->timestamp = time();
    }


    /**
     * Description:  执行GET请求操作
     * @author: JiaMeng <666@majiameng.com>
     * Updater:
     * @param $url
     * @param array $params
     * @param array $headers
     * @return string
     */
    protected function get($url, $params = [], $headers = [])
    {
        return \tinymeng\tools\HttpRequest::httpGet($url, $params,$headers);
    }

    /**
     * Description:  执行POST请求操作
     * @author: JiaMeng <666@majiameng.com>
     * Updater:
     * @param $url
     * @param array $params
     * @param array $headers
     * @return mixed
     */
    protected function post($url, $params = [], $headers = [])
    {
        $headers[] = 'Accept: application/json';//GitHub需要的header
        return \tinymeng\tools\HttpRequest::httpPost($url, $params,$headers);
    }
}
