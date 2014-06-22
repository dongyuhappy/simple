<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Dao;


use Simple\Exception\UnexpectedValueException;
use Simple\Model\Driver\NoSQL;
use Simple\Model\Vo\NoSQLVo;
use Simple\Model\Vo\Vo;

class NoSQLDao implements Dao
{
    private $_link;

    public function __construct(NoSQL $link)
    {
        $this->_link = $link;
    }

    /**
     * 获取单条数据
     * @param Vo $vo 实体对象
     * @param int $id 此字段，目前无效
     * @return Vo
     * @throws UnexpectedValueException
     */
    public function get(Vo $vo, $id = 0)
    {
        if (!($vo instanceof NoSQLVo))
            throw new UnexpectedValueException('vo对象必须为NoSQLVo');
        $key = $vo->getKey();
        $data = $this->_link->get($key, $vo->getCas());
        if (!$data)
            return false;
        $vo->fromArray($data);
        return true;
    }

    /**
     * @param Vo $vo
     * @param bool $fillPriKey 是否需用last_insert_id填充主键的值，只在关系型数据库中有效
     * @return mixed
     * @throws UnexpectedValueException
     */
    public function add(Vo $vo, $fillPriKey = true)
    {
        if (!($vo instanceof NoSQLVo))
            throw new UnexpectedValueException('vo对象必须为NoSQLVo');
        $key = $vo->getKey();
        return $this->_link->add($key, $vo->toSave(), $vo->getExpiredTime());
    }

    /**
     * 修改
     * @param Vo $vo
     * @param array $fields 要更新的字段
     * @return mixed
     * @throws UnexpectedValueException
     */
    public function update(Vo $vo, $fields = array())
    {
        if (!($vo instanceof NoSQLVo))
            throw new UnexpectedValueException('vo对象必须为NoSQLVo');
        return $this->_link->update($vo->getKey(), $vo->toSave(), $vo->getExpiredTime());
    }

    /**
     * 删除
     * @param Vo $vo
     * @return mixed
     * @throws UnexpectedValueException
     */
    public function delete(Vo $vo)
    {
        if (!($vo instanceof NoSQLVo))
            throw new UnexpectedValueException('vo对象必须为NoSQLVo');

        return $this->_link->delete($vo->getKey());
    }


    /**
     * cas更新
     * @param Vo $vo
     * @return bool
     * @throws \Simple\Exception\UnexpectedValueException
     */
    public function  cas(Vo $vo)
    {
        if (!($vo instanceof NoSQLVo))
            throw new UnexpectedValueException('vo对象必须为NoSQLVo');
        return $this->_link->cas($vo->getKey(), $vo->toSave(), $vo->getExpiredTime(), $vo->getCas());
    }

} 