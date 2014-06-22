<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Cycle;


abstract class Response
{
    /**
     * @var int 状态码
     */
    protected $status = 0;

    /**
     * @var array 返回给客户端的header信息
     */
    protected $header = array();


    /**
     * @var array 返回给客户端的数据
     */
    protected $body = array();


    /**
     * @param array $header
     */
    public function __construct($header)
    {
        $this->setHeader($header);
    }


    /**
     * @param string $key
     * @param mixed $val
     */
    public function addBody($key, $val)
    {
        $this->body[$key] = $val;
    }


    /**
     * 默认取整个body，否者取指定key的数据
     * @param null $key
     * @return mixed
     */
    public function getBody($key = null)
    {
        if ($key === null)
            return $this->body;
        return $this->body[$key];
    }

    /**
     * @param array $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 把数据返回给客户端
     * @return mixed
     */
    abstract function toClient();
}