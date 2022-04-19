# wechat

# 微信SDK说明文档

* officialAccount   公众号
* payment 微信支付
* miniProgram 小程序
* openPlatform 开放平台
* work 企业微信
* openWork 企业微信开放平台

## 1.安装
> composer require tinymeng/wechat dev-master  -vvv

### 目录结构

```
.
├── example                          实例代码源文件目录
├── src                              代码源文件目录
│   ├── Kernel
│   │   └── Event.php              事件基类
│   ├── OpenPlatform                开放平台
│   │   ├── Server                 回调事件处理实例
│   │   └── Application.php        开放平台实现类
│   └── Factory.php                 工厂类
├── composer.json                    composer文件
├── LICENSE                          MIT License
└── README.md                        说明文件
```


#### openPlatform 开放平台

* 初始化使用

```php
<?php
use tinymeng\Wechat\Factory;
    $config = [
        'app_id' => 'wxaa40f5*******',
        'app_secret' => '651911d4**************',
        'token'=>'tinymeng',
        'aes_key'=>'RrVA0dy******************************'
    ];
    $openPlatform = Factory::openPlatform($config);

```

* 处理事件

```php
<?php
use tinymeng\Wechat\Factory;
use tinymeng\Wechat\OpenPlatform\Server\Guard;
class Wechat{
    /**
     * Description:  获取组件ticket(微信每10分钟推送ticket_token)
     * Author: JiaMeng <666@majiameng.com>
     * Updater:
     */
    public function ticket(){
        $config = [];//同上👆
        $server = Factory::openPlatform($config)->server;
        // 处理授权成功事件
//        $server->push(function($params){
              /**
               * TODO...
               * $params 为微信推送的通知内容，不同事件不同内容，详看微信官方文档
               * 获取授权公众号 AppId： $params['AuthorizerAppid']
               * 获取 AuthCode：$params['AuthorizationCode']
               * 然后进行业务处理，如存数据库等...
               */
//        },Guard::EVENT_AUTHORIZED);
        /** 或者这样使用 */
        // 处理授权成功事件
        $server->push(new TicketService(),Guard::EVENT_AUTHORIZED);
        // 处理授权取消事件
        $server->push(new TicketService(),Guard::EVENT_UNAUTHORIZED);
        // 处理授权更新事件
        $server->push(new TicketService(),Guard::EVENT_UPDATE_AUTHORIZED);
        // 处理推送VerifyTicket事件 
        $server->push(new TicketService(),Guard::EVENT_COMPONENT_VERIFY_TICKET);
        $server->serve()->success();
    }
}

/**
 * 事件实现类
 */
class TicketService{
    //$params,为微信解密后的数据
    public function authorized($params){
    }
    public function unauthorized($params){
    }
    public function updateauthorized($params){
    }
    public function component_verify_ticket($params){
    }
    public function default($params){
        //$params,为微信解密后的数据    
    }
}

```

> 大家如果有问题要交流，就发在这里吧： [wechat](https://github.com/majiameng/wechat/issues/1) 交流 或发邮件 666@majiameng.com
