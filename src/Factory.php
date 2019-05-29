<?php

/**
 * 第三方登陆实例抽象类
 * @author: JiaMeng <666@majiameng.com>
 */

namespace tinymeng\Wechat;

use tinymeng\Wechat\Connector\GatewayInterface;
use tinymeng\Wechat\Helper\Str;

abstract class Factory
{

    /**
     * Description:  init
     * @author: JiaMeng <666@majiameng.com>
     * Updater:
     * @param $gateway
     * @param null $config
     * @return mixed
     * @throws \Exception
     */
    protected static function make($gateway, $config = null)
    {
        $baseConfig = [
            'app_id'    => '',
            'app_secret'=> '',
            'callback'  => '',
            'scope'     => '',
        ];
        $gateway = Str::uFirst($gateway);
        $class = __NAMESPACE__ . '\\Gateways\\' . $gateway;
        if (class_exists($class)) {
            $app = new $class(array_replace_recursive($baseConfig,$config));
            if ($app instanceof GatewayInterface) {
                return $app;
            }
            throw new \Exception("第三方微信基类 [$gateway] 必须继承抽象类 [GatewayInterface]");
        }
        throw new \Exception("第三方微信基类 [$gateway] 不存在");
    }

    /**
     * Description:  __callStatic
     * @author: JiaMeng <666@majiameng.com>
     * Updater:
     * @param $gateway
     * @param $config
     * @return mixed
     */
    public static function __callStatic($gateway, $config)
    {
        return self::init($gateway, ...$config);
    }

}
