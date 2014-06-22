<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Vo;


abstract class NoSQLVo extends Vo
{

    /**
     * memcached 获取的时候会返回cas
     * @var mixed
     */
    protected $cas;

    /**
     * @return mixed
     */
    public function getCas()
    {
        return $this->cas;
    }


    /**
     * 获取写入到NoSQL的key
     * @return mixed
     */
    public abstract function getKey();

    /**
     * 过期时间
     * @return mixed
     */
    public abstract function getExpiredTime();
} 