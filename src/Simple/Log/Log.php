<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Log;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log
{

    /**
     * 日志对象集合
     * @var array
     */
    private static $_loggers = array();

    /**
     * 系统默认的日志格式化对象
     * @var null
     */
    private static $_formatter = null;


    /**
     * handler集合
     * @var array
     */
    private static $_Stream = array();


    /**
     *
     * 初始化默认格式化对象
     * @return void
     *
     */
    private static function initialize()
    {
        if (self::$_formatter == null) {
            $dateFormat = 'Y-m-d:H:i:s';
            $output = "[%datetime%] > %message% > %context% > %extra%\n";
            self::$_formatter = new LineFormatter($output, $dateFormat);
        }
    }


    public static function debug($name, $message, $context = array())
    {
        $logger = null;
        if (isset(self::$_loggers[$name])) {
            $logger = self::$_loggers[$name];
        } else {
            $logger = new Logger($name);
            $stream = new StreamHandler();
        }
    }

    public static function log($loggerName, $message, $context = array(), StreamHandler $handler = null, LineFormatter $formatter = null)
    {
        $logger = null;
        if (isset(self::$_loggers[$loggerName])) {
            $logger = self::$_loggers[$loggerName];

        } else {
            $logger = new Logger($loggerName);
        }

        if ($formatter == null) {
            self::initialize();
            $formatter = self::$_formatter;
        }
        $handler->setFormatter($formatter);

        //设置handler
        $logger->pushHandler($handler);


        $level = $handler->getLevel();

        if ($level == Logger::DEBUG) {
            $logger->addDebug($message, $context);
        }
    }


}