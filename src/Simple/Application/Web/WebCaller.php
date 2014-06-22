<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Web;


use Simple\Application\Web\Exception\WebCycleException;
use Simple\Cycle\Caller;
use Simple\Cycle\Request;

/**
 * 调用controller
 * Class WebCaller
 * @package Simple\Application\Web
 */
class WebCaller extends Caller
{


    /**
     * 调用具体的业务逻辑接口，通常来说是项目中的
     *      controller，也可以RPC调用
     * @param Request $request
     * @return mixed
     * @throws Exception\WebCycleException
     */
    public function toCall(Request $request)
    {

        $ret = parent::toCall($request);
        if (($ret instanceof WebResponse) === false) {
            throw new WebCycleException('接口返回的对象必须为WebResponse对象。');
        }

        return $ret;
    }

} 