<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Web\Util;


use Simple\Config\ConfigManager;

class URL {

    /**
     * 生成连接
     * @param null $m
     * @param null $a
     * @param array $p
     * @param null $g
     * @return string
     */
    public static function U($m = null, $a = null, $p = array(), $g = null)
    {
        $module = $m ? $m : _MODULE_;

        $action = $a ? $a : _ACTION_;
        $mVar = ConfigManager::get('module_var');
        $aVar = ConfigManager::get('action_var');
        $gVar = ConfigManager::get('group_var');
        $route = array();
        $route[$mVar] = $module;
        $route[$aVar] = $action;
        if ($g) {
            $route[$gVar] = $g;
        }
        return '?' . http_build_query(array_merge($route, $p));
    }


    /**
     * 跳转
     * @param $url
     * @param int $time
     */
    public static function redirect($url,$time = 0){
        $url = str_replace(array("\n", "\r"), '', $url);

        if(!headers_sent()){
            //报头还未发送，可以使用header
            if($time === 0){
                header('Location:'.$url);
            }else{
                header('Location:'.$url.';refresh:'.$time);
            }
        }else{
            $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            exit($str);
        }
    }
} 