<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Simple\Config\ConfigManager;
use Simple\Date\Date;

class LogUtil
{

    /**
     *  日志对象列表
     * @var array
     */
    private static $loggers = array();

    /**
     * 写入日志
     * @param $msg
     * @param $name
     * @param int $level
     * @param AbstractHandler $handler
     */
    public static function write($msg, $name, $level = Logger::INFO, AbstractHandler $handler = null)
    {
        $name .='_'.$level;
        $name = $name . '_' . date('Y-m-d-H', Date::now());
        if (isset(self::$loggers[$name])) {
            $logger = self::$loggers[$name];
            if ($handler != null) {
                $logger->pushHandler($handler);
            }
        } else {
            $logger = new Logger($name);
            if ($handler == null) {
                $path = ConfigManager::get('log');
                $handler = new StreamHandler($path . DIRECTORY_SEPARATOR . $name . '.log', $level);
                $formatter = new LineFormatter(ConfigManager::get('log_formatter'), 'Y-m-d H:i:s');
                $handler->setFormatter($formatter);
            }
            $logger->pushHandler($handler);
            self::$loggers[$name] = $logger;
        }
        if (is_scalar($msg) === false)
            $msg = json_encode($msg);
        $logger->log($level, $msg);


    }
} 