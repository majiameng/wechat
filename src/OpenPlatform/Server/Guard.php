<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace tinymeng\Wechat\openPlatform\Server;

/**
 * Class Guard
 * Author: Tinymeng <666@majiameng.com>
 * @package tinymeng\Wechat\OpenPlatform\Server
 */
class Guard extends Server
{
    /**
     * 授权事件类型
     */
    const EVENT_AUTHORIZED = 'authorized';
    const EVENT_UNAUTHORIZED = 'unauthorized';
    const EVENT_UPDATE_AUTHORIZED = 'updateauthorized';
    const EVENT_COMPONENT_VERIFY_TICKET = 'component_verify_ticket';

    /**
     * 消息与事件类型
     */
    const MSG_TYPE_EVENT = 'event';
    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_IMAGE = 'image';

    
}
