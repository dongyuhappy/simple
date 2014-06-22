<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Cycle;


abstract class Application
{

    /**
     * 项目中使用的Request对象
     * @var Request
     */
    protected $request = null;

    /**
     * 路由对象
     * @var Router
     */
    protected $router = null;

    /**
     * 项目的相关配置
     * @var array
     */
    protected $config = array();

    /**
     * 初始化项目
     * @param array $config 项目的配置信息
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }


    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }


    /**
     *
     * 执行项目
     * @return void
     */
    abstract public function run();


    /**
     * 创建Request对象
     * @param Router $router
     * @return Request
     */
    abstract public function createRequest(Router $router);


    /**
     * 生成router对象
     * @return Router
     */
    abstract public function createRouter();


    /**
     * 获取关系型数据库的连接配置信息
     * @param $name
     * @return mixed
     */
    abstract public function getDbConfig($name);


    /**
     * 获取NoSQL连接配置信息
     * @param $name
     * @return array
     */
    abstract public function getNoSQLConfig($name);
}