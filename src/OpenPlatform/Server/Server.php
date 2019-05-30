<?php
namespace tinymeng\Wechat\OpenPlatform\Server;

use tinymeng\Wechat\Helper\Encrypt;
use tinymeng\Wechat\Kernel\Event;

/**
 * Class Server
 * Author: Tinymeng <666@majiameng.com>
 * @package tinymeng\Wechat\OpenPlatform\Server
 */
class Server extends Event
{
    public $config  = array();

    /**
     * Name: 开启监听
     * Author: Tinymeng <666@majiameng.com>
     */
    public function serve(){
        $message = Encrypt::wxDecryptMsg($this->config);
        if(isset($message['InfoType'])){
            $this->trigger($message['InfoType'], $message);
        }elseif(isset($message['MsgType'])){
            $this->trigger($message['MsgType'], $message);
        }
        return $this;
    }

}

