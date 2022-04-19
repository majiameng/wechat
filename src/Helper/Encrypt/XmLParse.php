<?php
namespace tinymeng\Wechat\Helper\Encrypt;
use DOMDocument;

/**
 * XMLParse class
 * 提供提取消息格式中的密文及生成回复消息格式的接口.
 */
class XmLParse
{
	/**
	 * 提取出xml数据包中的加密消息
	 * @param string $xmltext 待提取的xml字符串
	 * @return string 提取出的加密消息字符串
	 */
	public function extract($xmltext)
	{ 
		try {
			$xml = new DOMDocument();
			$xml->loadXML($xmltext);
			$array_encrypt = $xml->getElementsByTagName('Encrypt');
            if(strpos($xmltext,'AppId') !== false) {
    			$array_a = $xml->getElementsByTagName('AppId');//AppId ToUserName
            }elseif(strpos($xmltext,'ToUserName') !== false) {
			    $array_a = $xml->getElementsByTagName('ToUserName');//AppId ToUserName
            }else{
                return array(ErrorCode::$ParseXmlError, null, null);
            }
			$encrypt = $array_encrypt->item(0)->nodeValue;
			$tousername = $array_a->item(0)->nodeValue;
			return array(0, $encrypt, $tousername);
		} catch (Exception $e) {
			 print $e . "\n";
			return array(ErrorCode::$ParseXmlError, null, null);
		}
	}

	/**
	 * 生成xml消息
	 * @param string $encrypt 加密后的消息密文
	 * @param string $signature 安全签名
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字符串
	 */
	public function generate($encrypt, $signature, $timestamp, $nonce)
	{
		$format = "<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>";
		return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
	}

    /**
     * 解析XML数据
     * Author: JiaMeng <666@majiameng.com>
     * Updater：
     * @param $xml
     * @return mixed
     */
    public function xmlToArray($xml){
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);
    }
}