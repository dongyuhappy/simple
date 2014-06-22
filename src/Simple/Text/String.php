<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Text;


use Simple\Exception\UnexpectedValueException;

class String
{
    /*
     *
     * 字符串是否以某个字串开始
     * @param string $string
     * @param string $prefix
     */
    public static function startWith($string, $prefix)
    {
        if (empty ($string) || empty ($prefix))
            throw new UnexpectedValueException('params is null');

        $len = strlen($prefix);
        if ($len > strlen($string))
            return false;
        for ($i = 0; $i < $len; $i++) {
            if ($string{$i} !== $prefix{$i})
                return false;
        }
        return true;
    }

    /*
     *
     * 字符串是否以某个字串结尾
     * @param string $string
     * @param string $prefix
     */
    public static function endWith($string, $prefix)
    {
        if (empty ($string) || empty ($prefix))
            throw new UnexpectedValueException('params is null');
        $strLen = strlen($string);
        $preLen = strlen($prefix);
        if ($strLen < $preLen)
            return false;
        $strStart = $strLen - $preLen;
        for ($i = 0; $i < $preLen; $i++) {
            if ($string{$strStart + $i} != $prefix{$i})
                return false;
        }
        return true;
    }


    /**
     * 处理 get/set的名称，例如 page_list,将会被处理为pageList,_后面的一个字符会变成大写
     * 注意：$name值会变成小写
     * @param $name
     * @return string
     */
    public static function processMethodName($name){
        $name = strtolower($name);
        if(strpos($name,'_') === false)
            return $name;
        $list = explode('_',$name);
        foreach($list as $k=>$v){
            if($k === 0)
                continue;
            $list[$k] = ucfirst($v);
        }
        return implode('',$list);
    }

} 