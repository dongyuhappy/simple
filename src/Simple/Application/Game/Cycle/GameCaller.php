<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Game\Cycle;


use Simple\Application\Game\Cycle\Exception\GameCycleException;
use Simple\Cycle\Caller;
use Simple\Cycle\Request;
use Simple\Cycle\Response;

/**
 *
 *接口的调用层，RPC实现也应该是在该层
 *
 */
class GameCaller extends  Caller
{

    /**
     *调用具体的业务逻辑接口，通常来说是项目中的controller,
     *  也可以RPC调用
     * @param Request $request
     * @return Response
     * @throws Exception\GameCycleException
     */
    public function toCall(Request $request)
    {
//

        $ret = parent::toCall($request);
        if (($ret instanceof GameResponse) === false) {
            throw new GameCycleException('接口返回的对象必须为:GameResponse对象。');
        }

        return $ret;
    }

} 