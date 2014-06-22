<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Dao;


use Simple\Model\Vo\Vo;

interface Dao
{

    /**
     * 获取单条数据
     * @param Vo $vo 实体对象
     * @param int $id 要查询的条件id
     * @return Vo
     */
    public function get(Vo $vo, $id);


    /**
     * @param Vo $vo
     * @param bool $fillPriKey 是否需用last_insert_id填充主键的值，只在关系型数据库中有效
     * @return mixed
     */
    public function add(Vo $vo, $fillPriKey = true);

    /**
     * 修改
     * @param Vo $vo
     * @param array $fields 要更新的字段
     * @return mixed
     */
    public function update(Vo $vo, $fields = array());

    /**
     * 删除
     * @param Vo $vo
     * @return mixed
     */
    public function delete(Vo $vo);
} 