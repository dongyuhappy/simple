<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Cycle;

use Simple\Bootstrap\Bootstrap;


/**
 * 请求对象
 * Class Request
 * @package Simple\Cycle
 */
abstract class Request
{


    /**
     * @param array $body 请求参数数据信息
     */
    public function __construct($body)
    {
        $this->body = $body;
        $this->auth();
    }


    /**
     * 请求数据
     * @var array
     */
    private $body = array();


    /**
     * 验证
     * @return mixed
     */
    abstract public function auth();


    /**
     * @param $key
     * @param $val
     */
    public function addBody($key, $val)
    {
        $this->body[$key] = $val;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }


    /**
     * 获取body里面某个字段的值
     * @param $key
     * @return mixed 如果字段存在，则返回对应的值。否者返回false
     */
    public function body($key)
    {
        //获取字段的值
        if (isset($this->body[$key]))
            return $this->body[$key];
        return false;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }


    /**
     * @return array
     */
    public function getHeader()
    {
        return Bootstrap::getHeader();
    }


}