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


* 实现接受事件

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
        $config = [
            'app_id' => 'wxaa40f5*******',
            'app_secret' => '651911d4**************',
            'token'=>'tinymeng',
            'aes_key'=>'RrVA0dy******************************'
        ];
        $server = Factory::openPlatform($config)->server;
        /** 或者这样使用 */
//        $server->push(function($params){
//            //$params,为微信解密后的数据
//        },Guard::EVENT_AUTHORIZED);
        $server->push(new TicketService(),Guard::EVENT_AUTHORIZED);
        $server->push(new TicketService(),Guard::EVENT_UNAUTHORIZED);
        $server->push(new TicketService(),Guard::EVENT_UPDATE_AUTHORIZED);
        $server->push(new TicketService(),Guard::EVENT_COMPONENT_VERIFY_TICKET);
        $server->serve();
        echo "success";
    }
}

/**
 * 功能实现类
 */
class TicketService{
    //$params,为微信解密后的数据
    public function authorized($params){
    }
    public function component_verify_ticket($params){
    }
    public function default($params){
        //$params,为微信解密后的数据       
    }
}

```

> 大家如果有问题要交流，就发在这里吧： [wechat](https://github.com/majiameng/wechat/issues/1) 交流 或发邮件 666@majiameng.com
