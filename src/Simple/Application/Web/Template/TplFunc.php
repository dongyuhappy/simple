<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Web\Template;

use Simple\Application\Web\Util\URL;

class TplFunc
{


    /**
     * 生成连接
     * @param null $m
     * @param null $a
     * @param array $p
     * @param null $g
     * @return string
     */
    public static function U($m = null, $a = null, $p = array(), $g = null)
    {
        return URL::U($m, $a, $p, $g);
    }
} 