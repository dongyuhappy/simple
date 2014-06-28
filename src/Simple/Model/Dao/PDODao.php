<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Dao;

use Simple\Debug\Debug;
use Simple\Exception\UnexpectedValueException;
use Simple\Model\Driver\MySQLPDO;
use Simple\Model\Exception\DbException;
use Simple\Model\Vo\PDOVo;
use Simple\Model\Vo\Vo;


/**
 * MySQL数据库访问层
 *
 */
class PDODao implements Dao
{

    /**
     * 数据库操作对象
     * @var \Simple\Model\Driver\MySQLPDO
     */
    protected $_link;


    /**
     * 构造方法
     * @param MySQLPDO $link
     */
    public function __construct(MySQLPDO $link)
    {
        $this->_link = $link;
    }

    /**
     * 获取单条数据
     * @param Vo $vo 实体对象
     * @param int $id 要查询的条件id
     * @throws UnexpectedValueException
     * @throws DbException
     * @return boolean 未找到数据返回false，找到一条数据返回true ，多条数据会产生异常
     */
    public function get(Vo $vo, $id)
    {
        if (!($vo instanceof PDOVo))
            throw new UnexpectedValueException('vo参数必须为MySQLVo对象。');

        $vo->createTb($this->_link);
        $priKey = $vo->getPrimaryKey();
        $where = " `{$priKey}`=:{$priKey} ";
        $tbName = $vo->getTableName();

        $fieldsArr = $vo->getFields();
        $fields = implode(",", $fieldsArr);
        $sql = "SELECT {$fields} FROM `" . $tbName . "` WHERE  " . $where;
        Debug::trace('查询数据:' . $sql, 'mysql');
        $sth = $this->_link->prepare($sql);
        $sth->bindValue(":{$priKey}", $id);
        Debug::trace(":{$priKey}={$id}", 'mysql');
        $ret = $sth->execute();
        if ($ret === false) {
            throw new DbException ($sth->errorInfo(), $sth->errorCode());
        }
        $result = $sth->fetchAll();
        if ($result === false) {
            throw new DbException ($sth->errorInfo(), $sth->errorCode());
        }
        if (!is_array($result) || !$result) {
            return false;
        }
        if (count($result) > 1)
            throw new DbException('查询到' . count($result) . '数据');
        $vo->fromArray($result[0]); //填充对象
        return true;
    }


    /**
     * 新增数据
     * @param Vo $vo
     * @param bool $fillPrikey
     * @return mixed|void
     * @throws \Simple\Model\Exception\DbException
     * @throws \Simple\Exception\UnexpectedValueException
     */
    public function add(Vo $vo, $fillPrikey = true)
    {
        if (!($vo instanceof PDOVo))
            throw new UnexpectedValueException('vo参数必须为PDOVo对象。');

        $tbName = $vo->getTableName(); //表名称

        //数据库中的字段,不包括主键
        $fields = $vo->getFields(array($vo->getPrimaryKey()));

        $insertFields = array_map(function ($item) {
            return '`' . $item . '`';
        }, $fields); // 数据库对应的字段


        $binds = array_map(function ($item) {
            return ':' . $item . '';
        }, $fields); // bind的字段值


        $sql = 'INSERT INTO `' . $tbName . '` ( ' . implode(",", $insertFields) . ' ) values ( ' . implode(',', $binds) . ' );';
        $sth = $this->_link->prepare($sql);
        if (!$sth)
            throw new DbException ('error sql' . $sql . ',' . $sth->errorInfo());

        foreach ($binds as $f) {
            $methodName = 'get' . lcfirst($f);
            $v = call_user_func_array(array(&$vo, $methodName), array());
            $sth->bindValue($f, $v);
        }
        try {
            $sth->execute();
        } catch (\PDOException $e) {
            throw new DbException ($e->getMessage());
        }

        if ($fillPrikey == true && $vo->getPrimaryKey()) {
            //填充主键的值
            $pkMethod = "set" . ucfirst($vo->getPrimaryKey());
            $lastInsertId = $this->_link->lastInsertId();
            call_user_func_array(array(&$vo, $pkMethod), array($lastInsertId));
        }

    }


    /**
     *
     * @param Vo $vo
     * @param array $fields 要更新的字段，如果全部更新，则不用传
     * @return int|mixed
     * @throws \Simple\Model\Exception\DbException
     * @throws \Simple\Exception\UnexpectedValueException
     */
    public function update(Vo $vo, $fields = array())
    {
        if (!($vo instanceof PDOVo))
            throw new UnexpectedValueException('vo参数必须为PDOVo对象。');

        $tbName = $vo->getTableName();

        //获取除主键外的所有字段
        $fields = $vo->getFields(array($vo->getPrimaryKey()));

        if (is_array($fields) && empty($fields) == false) {
            //只更新指定的字段
            $fields = array_intersect($fields, $fields);
        }

        //组装更新的语句
        $setArr = array_map(function ($f) {
            return "`{$f}`=:{$f}";
        }, $fields);
        $setStr = implode(',', $setArr);


        $priKey = $vo->getPrimaryKey();
        $where = " `{$priKey}`=:{$priKey} ";

        $sql = "UPDATE  `{$tbName}` SET " . $setStr . " WHERE " . $where;
        Debug::trace('update:'.$sql,'mysql');
        $sth = $this->_link->prepare($sql);
        if (!$sth)
            throw new DbException ('error sql' . $sql . ',' . $sth->errorInfo());
        foreach ($fields as $f) {
            $f = lcfirst($f);
            $methodName = 'get' . $f;
            $v = call_user_func_array(array(&$vo, $methodName), array());
            $sth->bindValue($f, $v);
        }

        $methodName = 'get' . ucfirst($priKey);
        $sth->bindValue($priKey, call_user_func_array(array(&$vo, $methodName), array()));
        $ret = $sth->execute(); //执行SQL语句
        if ($ret === false) {
            throw new DbException ($sth->errorInfo(), $sth->errorCode());
        }
        return $sth->rowCount();
    }


    /**
     * @param Vo $vo
     * @return int 返回删除数据的行数
     * @throws \Simple\Model\Exception\DbException
     * @throws \Simple\Exception\UnexpectedValueException
     */
    public function delete(Vo $vo)
    {
        if (!($vo instanceof PDOVo))
            throw new UnexpectedValueException('vo参数必须为PDOVo对象。');
        $priKey = $vo->getPrimaryKey();
        $where = " `{$priKey}`=:{$priKey} ";
        $tbName = $vo->getTableName();
        $sql = "DELETE FROM `" . $tbName . "` WHERE  " . $where;
        Debug::trace('delete:' . $sql, 'mysql');
        $sth = $this->_link->prepare($sql);
        $sth->bindValue(":{$priKey}", call_user_func_array(array(&$vo, "get" . ucfirst($priKey)), array()));
        $ret = $sth->execute();
        if ($ret === false) {
            throw new DbException ($sth->errorInfo(), $sth->errorCode());
        }
        return $sth->rowCount();
    }
}