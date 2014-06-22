<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Web;


use Simple\Application\Web\Util\URL;

class BaseController {

    /**
     * 跳转
     * @param $url
     * @param int $time
     */
    protected  function redirect($url,$time=0){
        URL::redirect($url,$time);
    }
} 