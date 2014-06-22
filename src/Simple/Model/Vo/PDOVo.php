<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Vo;


use Simple\Model\Driver\MySQLPDO;
use Simple\Model\Exception\DbException;

abstract class PDOVo extends Vo
{

    /**
     * 设置查询或者更新的条件
     * @var string
     */
    protected $where = null;


    /**
     * 获取数据表的名称
     * @return mixed|string
     */
    public abstract function getTableName();

    /**
     * 获取创建数据表的SQL语句
     * @return mixed|string
     */
    public abstract function installTableSQL();

    /**
     * 是否在运行时去创建数据表
     * @return boolean
     */
    public abstract function isRuntimeInstallTable();

    /**
     * 获取主键
     * @return mixed
     */
    public abstract function getPrimaryKey();


    /**
     * 创建数据表
     *
     * @param MySQLPDO $pdo
     * @throws DbException
     */
    public function createTb(MySQLPDO $pdo)
    {
        if ($this->isRuntimeInstallTable()) {

            $isCreate = $pdo->exec($this->installTableSQL());
            if ($isCreate === false) {
                $error = json_encode($pdo->errorInfo());
                throw new DbException ('create table ' . $this->getTableName() . ' fail ' . $error);
            }
        }
    }
} 