<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Driver;

use Simple\Model\Exception\DbException;

class NoSQL implements NoSQLInterface
{
    /**
     * 连接句柄
     * @link http://www.php.net/manual/zh/book.memcached.php
     * @var \Memcached|null
     */
    private $dbh = null;

    public function  __construct($host, $port, $type = NoSQLType::MEMCACHED)
    {
        $this->dbh = new \Memcached();
        $this->dbh->addServer($host, $port);
    }

    /**
     * 新增key
     * @param $key
     * @param $val
     * @param $expireTime
     * @return bool
     */
    public function add($key, $val, $expireTime)
    {
        $ret = $this->dbh->add($key, $val, $expireTime);
        if ($ret === false)
            $this->error('add', $key);
        return true;
    }


    /**
     * 替换已经存在的key
     * @param $key
     * @param $val
     * @param $expireTime
     * @return bool
     */
    public function update($key, $val, $expireTime)
    {
        $ret = $this->dbh->replace($key, $val, $expireTime);
        if ($ret === false)
            $this->error('update', $key);
        return true;
    }


    /**
     * 获取多条数据记录
     * @param array $keys
     * @return mixed
     */
    public function getMulti(array $keys)
    {
        //保证返回的key的顺序和请求时一致
        $data = $this->dbh->getMulti($keys, $cas, \Memcached::GET_PRESERVE_ORDER);
        if ($data === false) {
            if ($this->dbh->getResultCode() == \Memcached::RES_NOTFOUND) {
                return array();
            } else {
                $this->error('getMulti', json_encode($keys));
            }
        }

        return $data;
    }


    /**
     * 获取数据
     * @param $key
     * @param $cas_token
     * @return bool|mixed
     *        如果key不存在返回 false
     *        获取数据发生异常，会生成一个exception
     *        正常获取到数据，就返回数据
     */
    public function get($key, &$cas_token)
    {
        $ret = $this->dbh->get($key, $cas_token);
        if ($this->dbh->getResultCode() === \Memcached::RES_NOTFOUND)
            return false;
        if ($ret === false) {
            $this->error('get', $key);
        }
        return $ret;
    }


    /**
     * cas
     * @param $key
     * @param $val
     * @param $expireTime
     * @param $cas_token
     * @return bool
     */
    public function cas($key, $val, $expireTime, &$cas_token)
    {
        $ret = $this->dbh->cas($cas_token, $key, $val, $expireTime);
        if ($ret === false)
            $this->error('cas', $key);
        return true;
    }


    /**
     * 错误处理
     * @param $op
     * @param $key
     * @throws \Simple\Model\Exception\DbException
     */
    private function error($op, $key)
    {
        throw new DbException($op . '操作' . $key . '出错，错误码为:' . $this->dbh->getResultCode() . '，具体信息为:' . $this->dbh->getResultMessage());
    }


    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        $ret = $this->dbh->delete($key);
        if ($ret === false)
            $this->error('delete', $key);
        return true;
    }


}


class NoSQLType
{
    const MEMCACHED = 1;
    const  REDIS = 2;
}