<?php
namespace tinymeng\Wechat\Connector;

/**
 * 微信公众号返回
 */
abstract class Response
{

    public $appid;//公众号appid
    public $params;//微信回调数据
    public $keyword;//用户发送消息
    public $msgType = 'text';//消息类型

    /** @var string $textTpl xml文本消息模版 */
    public $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        <FuncFlag>0</FuncFlag>
        </xml>";


}
