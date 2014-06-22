<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Game\Cycle;


use Simple\Cycle\Application;
use Simple\Cycle\Request;
use Simple\Cycle\Router;

abstract class GameApplication extends Application
{
    /**
     *
     * 执行项目
     * @return GameResponse
     */
    public function run()
    {

        $router = $this->createRouter();
        $this->createRequest($router);
        $caller = new GameCaller();
        $resp = $caller->toCall($this->request);
        return $resp;
    }

    /**
     * 创建Request对象
     * @param Router $router
     * @return Request
     */
    public function createRequest(Router $router)
    {
        $params = array_merge($_GET, $_POST);

        $this->request = new GameRequest(array($router->getModule(), $router->getAction()), $params);
        return $this->request;
    }


    /**
     * 生成router对象
     * @return Router
     */
    public function createRouter()
    {
        $router = new GameRouter();
        $this->router = $router;
        return $this->router;
    }


}