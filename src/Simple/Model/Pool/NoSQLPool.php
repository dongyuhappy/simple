<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Pool;


use Simple\Config\ConfigManager;
use Simple\Model\Driver\NoSQL;

class NoSQLPool
{

    /**
     * 用来存放连接
     * @var array
     */
    private static $_map = array();


    public static function redis($name)
    {
        // TODO
    }


    /**
     * 获取连接
     * @param $name
     * @return NoSQL
     */
    public static function memcache($name)
    {
        if (isset(self::$_map[$name])) {
            return self::$_map[$name];
        }

        $cnf = ConfigManager::getNoSQLConfig($name);
        $nosql = new NoSQL($cnf['host'], $cnf['port']);
        self::$_map[$name] = $nosql;
        return $nosql;
    }


} 