<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Pool;


use Simple\Config\ConfigManager;
use Simple\Model\Driver\MySQLPDO;

class MySQLPool
{

    /**
     * 数据库对象容器
     * @var array
     */
    private static $_map = array();

    /**
     * 获取数据库连接
     * @param $name
     * @return MySQLPDO
     */
    public static function get($name)
    {
        if (isset(self::$_map[$name]))
            return self::$_map[$name];

        //数据库配置信息
        $cnf = ConfigManager::getDbConfig($name);

        $pdo = new MySQLPDO($cnf['host'], $cnf['port'], $cnf['username'],
            $cnf['passwd'], $cnf['database'], $cnf['charset']
        );

        self::$_map[$name] = $pdo;
        return $pdo;
    }
} 