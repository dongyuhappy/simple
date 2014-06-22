<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Cycle;



use Simple\Application\Game\Cycle\Exception\GameCycleException;

class Caller {

    /**
     * 调用具体的业务逻辑接口，通常来说是项目中的
     *      controller，也可以RPC调用
     * @param Request $request
     * @return mixed
     * @throws \Simple\Application\Game\Cycle\Exception\GameCycleException
     */
    public  function toCall(Request $request){
        $head = $request->getHeader();
        $module = ucfirst($head[0]);
        $action = $head[1];

        // TODO 获取类的命名空间
        $cls = new \ReflectionClass(APP_TOP_NAMESPACE . '\\Controller\\' . $module . 'Controller');
        $instance = null;

        //优先调用init方法
        if ($cls->hasMethod('__init__')) {
            $initMethod = $cls->getMethod('__init__');
            if ($initMethod->isPublic() == true) {
                $instance = $cls->newInstanceArgs(array());
                $initMethod->invokeArgs($instance, array());
            }
        }

        $method = $cls->getMethod($action);
        if ($method->isPublic() == false) {
            throw new GameCycleException('无法调用接口:' . $module . '.' . $action);
        }

        if ($instance == null) {
            $instance = $cls->newInstanceArgs(array());
        }

        $ret = $method->invokeArgs($instance, array($request));
        if (($ret instanceof Response) === false) {
            throw new GameCycleException('接口返回的对象必须为:Response对象。');
        }

        return $ret;
    }
} 