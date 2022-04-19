<?php
namespace tinymeng\Wechat\Helper;

use tinymeng\Wechat\Helper\Encrypt\WxBizMsgCrypt;
use tinymeng\Wechat\Helper\Encrypt\XmLParse;

class Encrypt{

    public $timestamp;
    public $nonce;
    public $msg_sign;

    public $oldXml;             //解密前XML数据
    public $encryptMsg = '';    //解密后的数据(xml)
    public $msg;                //解密后的数据(array)

    /**
     * Description: 微信消息解密
     * Author: JiaMeng <666@majiameng.com>
     * 原微信使用mdecrypt扩展进行解密,由于php7.1以上版本废除此函数
     * 已改用openssl扩展openssl_decrypt()函数来解密
     */
    public function wxDecryptMsg($config){
        /** 解密参数 */
        $this->timestamp = empty($_GET['timestamp'])    ? ""    : trim($_GET['timestamp']);
        $this->nonce = empty($_GET['nonce'])            ? ""    : trim($_GET['nonce']);
        $this->msg_sign = empty($_GET['msg_signature']) ? ""    : trim($_GET['msg_signature']);
        /** 解密前XML数据 */
        $this->oldXml = file_get_contents ( 'php://input' );
        if (empty($this->oldXml)) {
            exit('empty');
        }

        $pc = new WxBizMsgCrypt($config['app_id'],$config['token'], $config['aes_key']);
        $errCode = $pc->decryptMsg($this->msg_sign, $this->timestamp, $this->nonce, $this->oldXml, $this->encryptMsg);
        if ($errCode != 0) {
            new \Exception('WxBizMsgCrypt fail, errCode:'.$errCode);
        }
        $xmlparse = new XMLParse;
        $this->msg = $xmlparse->xmlToArray($this->encryptMsg);
        return $this;
    }
}