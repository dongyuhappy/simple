<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Config;


use Simple\Bootstrap\Bootstrap;
use Simple\Config\Exception\ConfigException;

class ConfigManager
{

    /**
     * 系统的默认配置
     * @var array
     */
    private static $_sys = null;


    /**
     * 查找配置信息
     *  先从项目的配置信息中开始找
     * @param $name
     * @return mixed
     * @throws Exception\ConfigException
     */
    public static function get($name)
    {

        //查找项目中的的配置
        $appConfig = Bootstrap::getApp()->getConfig();

        if (isset($appConfig[$name]))
            return $appConfig[$name];

        if (self::$_sys === null) {
            self::$_sys = array_merge(include_once('config.php'),include_once('web_config.php'));
        }


        if (isset(self::$_sys[$name])) {
            return self::$_sys[$name];
        }
        throw new ConfigException('找不到key为' . $name . '的配置信息');
    }


    /**
     * 获取数据库的配置信息
     * @param $name
     * @return array
     */
    public static function getDbConfig($name)
    {
        $app = Bootstrap::getApp();
        return $app->getDbConfig($name);
    }


    /**
     * 获取NOSQL的配置信息
     * @param $name
     * @return mixed
     */
    public static function getNoSQLConfig($name)
    {
        $app = Bootstrap::getApp();
        return $app->getNoSQLConfig($name);
    }


}