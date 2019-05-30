<?php

namespace tinymeng\Wechat\Kernel;

class Event
{
    protected static $listens  = array();

    /**
     * 默认事件回调
     * @var string
     */
    private $callback = 'default';
    /**
     * 是否监听一次性事件,改为false则可以多次触发
     * @var bool
     */
    private $once = true;

    /**
     * Name: 绑定事件
     * Author: Tinymeng <666@majiameng.com>
     * @param $class
     * @param $event
     * @return bool
     */
    public function push($class,$event){

        $callback = is_callable($class) ? $class : [$class,$event];
        $callback = is_callable($callback) ? $callback : [$class,$this->callback];
        if(!is_callable($callback)){
            return false;
        }

        self::$listens[$event][]    = array('callback'=>$callback, 'once'=>$this->once);
        return true;
    }

    /**
     * Name: 触发事件
     * Author: Tinymeng <666@majiameng.com>
     * @return bool|void
     */
    public function trigger(){
        if(!func_num_args()) return;
        $args    = func_get_args();
        $event   = array_shift($args);

        if(!isset(self::$listens[$event]))
            return false;

        foreach((array) self::$listens[$event] as $index=>$listen){

            $callback  = $listen['callback'];
            $listen['once'] && self::remove($event, $index);
            call_user_func_array($callback, $args);
        }
    }

    /**
     * Name: 注销事件
     * Author: Tinymeng <666@majiameng.com>
     * @param $event
     * @param null $index
     */
    public function remove($event, $index=null){
        if(is_null($index))
            unset(self::$listens[$event]);
        else
            unset(self::$listens[$event][$index]);
    }

}