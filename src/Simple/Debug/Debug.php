<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Debug;


use Monolog\Handler\AbstractHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Logger;
use Simple\Config\ConfigManager;

class Debug
{

    /**
     * 日志对象容器
     * @var array
     */
    private static $_map = array();


    /**
     * 获取一个日志对象
     * @param string $name
     * @param AbstractHandler $handler
     * @param int $level
     * @return Logger
     */
    private static function get($name = 'debug', AbstractHandler $handler = null, $level = Logger::INFO)
    {
        if (isset(self::$_map[$name]) && self::$_map[$name] != null) {
            return self::$_map[$name];
        }

        //初始化
        $logger = new Logger($name);
        $handler = $handler == null ? new ChromePHPHandler() : $handler;
        $logger->pushHandler($handler, $level);
        self::$_map[$name] = $logger;
        return $logger;
    }


    /**
     *
     * 输出调试信息,应尽量避免使用echo输出
     * @param $msg
     * @param string $name
     * @param AbstractHandler $handler
     * @param int $level
     */
    public static function trace($msg, $name = 'debug', AbstractHandler $handler = null, $level = Logger::INFO)
    {
        //只有在debug的情况下才会输出调试信息
        $debug = ConfigManager::get('debug');
        if($debug === false)
            return ;

        $logger = self::get($name, $handler, $level);
        if (is_scalar($msg) === false) {
            $msg = json_encode($msg);
        }
        $logger->addRecord($level, $msg);

    }

} 