<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Dao;


use Simple\Model\Exception\DaoException;
use Simple\Model\Exception\DbException;

class MySQLDao extends PDODao
{

    /**
     * 查询条件
     * @var string null
     */
    private $_where = '';

    /**
     * 数据表的名称
     * @var string
     */
    private $_table = '';

    /**
     * 要查询的字段，如果为空则查询所有字段
     * @var array
     */
    private $_field = array();

    /**
     * limit 0，10。第一个下标为start，第二个为size
     * @var array
     */
    private $_limit = array();


    /**
     * order排序字段
     * @var string
     */
    private $_order = '';


    /**
     * @param string $tb 数据表名称
     * @return $this
     */
    public function table($tb)
    {
        $this->_table = $tb;
        return $this;
    }

    /**
     * @param array $field 字段名称
     * @return $this
     */
    public function field($field)
    {
        $this->_field = $field;
        return $this;
    }


    /**
     * 设置limit 第一个下标为start，第二个为size
     * @param array $limit
     * @return $this
     */
    public function limit($limit = array())
    {
        $this->_limit = $limit;
        return $this;
    }

    /**
     * 设置where
     * @param string $where
     *  例如 id=%d ，=后面的值必须为sprintf函数的format参数
     * @param array $val
     * @return $this
     */
    public function where($where, $val)
    {

        $val = array_map(function ($v) {
            return str_replace("'", "''", $v);
        }, $val);

        $this->_where = vsprintf($where, $val);
        return $this;
    }

    /**
     * order
     * @param $order
     * @return $this
     */
    public function order($order)
    {
        $this->_order = $order;
        return $this;
    }

    /**
     * 获取数据
     * @return array
     * @throws \Simple\Model\Exception\DaoException
     * @throws \Simple\Model\Exception\DbException
     */
    public function fetchAll()
    {
        if (empty($this->_table))
            throw new DaoException('tablename 不能为空。');
        $sqlArr = array();
        $sqlArr[] = 'SELECT ';

        //字段
        if (empty($this->_field)) {
            $sqlArr[] = ' * ';
        } else {
            //使用设定的字段
            $sqlArr[] = implode(',', array_map(function ($v) {
                return '`' . $v . '`';
            }, $this->_field));
        }

        //where
        $sqlArr[] = ' FROM ' . $this->_table . ' ';

        //where
        if (!empty($this->_where)) {
            $sqlArr[] = ' WHERE ' . $this->_where;
        }
        //order
        if (!empty($this->_order)) {
            $sqlArr[] = ' ' . $this->_order;
        }

        //limit
        if (!empty($this->_limit) && isset($this->_limit[0])) {
            $sqlArr[] = ' LIMIT ' . intval($this->_limit[0]);
            if (isset($this->_limit[1])) {
                $sqlArr[] = ',' . intval($this->_limit[1]);
            }
        }
        $sth = $this->_link->prepare(implode(' ', $sqlArr));
        $ret = $sth->execute();
        if ($ret === false) {
            throw new DbException ($sth->errorInfo(), $sth->errorCode());
        }
        $result = $sth->fetchAll();
        if ($result === false) {
            throw new DbException ($sth->errorInfo(), $sth->errorCode());
        }
        return $result;

    }


}