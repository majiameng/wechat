<?php
namespace tinymeng\Wechat\Helper;

use tinymeng\Wechat\Helper\Encrypt\WxBizMsgCrypt;
use tinymeng\Wechat\Helper\Encrypt\XmLParse;

class Encrypt{


    /**
     * Description: 微信消息解密
     * Author: JiaMeng <666@majiameng.com>
     * 原微信使用mdecrypt扩展进行解密,由于php7.1以上版本废除此函数
     * 已改用openssl扩展openssl_decrypt()函数来解密
     */
    static public function wxDecryptMsg($config){
        /** 解密参数 */
        $timestamp = empty($_GET['timestamp'])    ? ""    : trim($_GET['timestamp']);
        $nonce = empty($_GET['nonce'])            ? ""    : trim($_GET['nonce']);
        $msg_sign = empty($_GET['msg_signature']) ? ""    : trim($_GET['msg_signature']);
        /** 解密前XML数据 */
        $postStr = file_get_contents ( 'php://input' );
        if (empty($postStr)) {
            exit('empty');
        }

        $pc = new WxBizMsgCrypt($config['app_id'],$config['token'], $config['aes_key']);

        $encryptMsg = '';//解密后的数据
        $errCode = $pc->decryptMsg($msg_sign, $timestamp, $nonce, $postStr, $encryptMsg);
        if ($errCode != 0) {
            new \Exception('WxBizMsgCrypt fail, errCode:'.$errCode);
        }
        
        $xmlparse = new XMLParse;
        return $xmlparse->xmlToArray($encryptMsg);
    }
}