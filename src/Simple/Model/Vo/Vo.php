<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Vo;


use Simple\Model\Exception\VoException;
use Simple\Text\String;

/**
 *
 * 实体对象基类
 */
class Vo
{
    protected $clientField = array(); //返回给客户端的字段,默认为空数组，返回所有的属性字段
    protected $fieldPrefix = "_"; //所有属性字段的前缀


    /*
     *
     * 检查属性名称的合法性
     * @param string $field
     * @throws SimpleException
     */
    private function fieldCheck($field)
    {
        if (String::startWith($field, $this->fieldPrefix) == false) {
            print_r(debug_backtrace());
            throw new VoException ('非法字段' . $field);
        }
    }

    /**
     *
     * 魔术set方法
     * @param string $field
     * @param string $val
     */
    public function __set($field, $val)
    {

        $this->fieldCheck($field);
        $this->$field = $val;
    }

    /**
     *
     * 魔术get方法
     * @param string $field
     * @return mixed 字段的值
     */
    public function __get($field)
    {
        $this->fieldCheck($field);
        return $this->$field;
    }

    /*
     *
     * 魔术call方法,未定义的方法中，只支持set和get方法
     * @param string $name
     * @param array $args
     * @param mixed 设置或者获取的属性的值
     */
    public function __call($name, $args)
    {

        $isGet = String::startWith($name, "get");
        if ($isGet == true) {
            $proto = '_' . lcfirst(substr($name, 3));
            return $this->__get($proto); //get方法
        }

        //set方法
        $isSet = String::startWith($name, 'set');

        if (!$isSet)
            throw new VoException ('不合法的方法' . $name);

        if (count($args) < 1)
            throw new VoException ($name . "的参数不能为空。");

        $proto = '_' . lcfirst(substr($name, 3));
        $this->__set($proto, $args [0]);
        return $args[0];
    }

    /**
     * 获取实体类的所有的属性字段
     *
     * @return array 实体类的所有的属性字段
     */
    public function getProperty()
    {
        $all = get_object_vars($this);
        $actProperty = array();
        foreach ($all as $property => $val) {
            if (String::startWith($property, $this->fieldPrefix) && strcasecmp($property, $this->fieldPrefix) !== 0) {
                $actProperty [] = $property;
            }
        }
        return $actProperty;
    }


    /**
     * 获取数据库的字段
     * @param array $filter 要过滤掉的字段
     * @return array
     */
    public function getFields($filter = array())
    {
        $fields = array_map(function ($item) {
            return substr($item, 1);
        }, $this->getProperty());
        return array_diff($fields, $filter);
    }


    /**
     *
     * 把数组格式化为对象
     * @param array $data
     */
    public function fromArray($data)
    {
        foreach ($data as $f => $v) {
            $methondName = "set" . ucfirst($f);
            call_user_func_array(array(&$this, $methondName), array($v));
        }
    }


    /**
     * 把对象格式化为数组
     * @param array $filter 要过滤掉的字段
     * @param int $case 对字段进行 大写(1) 小写(-1) 保持原样(0)处理
     * @return array
     */
    public function toArray($filter = array(), $case = -1)
    {
        if ($case == -1) {
            $filter = array_map(function ($item) {
                return strtolower($item);
            }, $filter);
            //全部转换为小写
        }

        $property = $this->getProperty();
        $data = array();
        foreach ($property as $f) {
            $f = substr($f, 1);

            //去掉要过滤的字段
            $methodName = "get" . ucfirst($f);
            if ($case == -1) {
                $f = strtolower($f);
            }
            if (in_array($f, $filter))
                continue;


            $v = call_user_func_array(array(&$this, $methodName), array());
            $data[$f] = $v;
        }
        return $data;
    }


    /**
     * 保存到数据库中的字段必须是原样
     * @param array $filter
     * @return array
     */
    public function toSave($filter = array())
    {
        return $this->toArray($filter, 0);
    }

}