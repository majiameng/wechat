<?php

/**
 * 第三方登陆实例抽象类
 * @author: JiaMeng <666@majiameng.com>
 */

namespace tinymeng\Wechat;

use tinymeng\Wechat\Connector\GatewayInterface;
use tinymeng\Wechat\Helper\Str;

/**
 * Class Factory.
 *
 * @method static \tinymeng\Wechat\Payment\Application            payment(array $config)
 * @method static \tinymeng\Wechat\MiniProgram\Application        miniProgram(array $config)
 * @method static \tinymeng\Wechat\OpenPlatform\Application       openPlatform(array $config)
 * @method static \tinymeng\Wechat\OfficialAccount\Application    officialAccount(array $config)
 * @method static \tinymeng\Wechat\BasicService\Application       basicService(array $config)
 * @method static \tinymeng\Wechat\Work\Application               work(array $config)
 */
class Factory
{
    /**
     * @param string $name
     * @param array  $config
     *
     * @return \EasyWeChat\Kernel\ServiceContainer
     */
    public static function make($name, array $config)
    {
        $baseConfig = [
            'app_id'    => '',
            'app_secret'=> '',
            'callback'  => '',
            'scope'     => '',
        ];
//        $namespace = Str::uFirst($name);

        $application = "\\tinymeng\Wechat\\{$name}\Application";
        if (class_exists($application)) {
//            $app = new $class(array_replace_recursive($baseConfig,$config));
//            if ($app instanceof GatewayInterface) {
//                return $app;
//            }
//            throw new \Exception("第三方微信基类 [$gateway] 必须继承抽象类 [GatewayInterface]");
            return new $application($config);
        }
        throw new \Exception("第三方微信基类 [$application] 不存在");
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
