<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Web;



use Simple\Cycle\Application;
use Simple\Cycle\Request;
use Simple\Cycle\Router;

/**
 *
 * Class WebApplication
 * @package Simple\Application\Web
 */
abstract class WebApplication extends Application{

    /**
     *
     * 执行项目
     * @return WebResponse
     */
    public function run()
    {
        $router = $this->createRouter();
        $this->createRequest($router);
        $caller = new WebCaller($this->request);
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
        $params = array_merge($_GET,$_POST);
        $this->request = new WebRequest(array($router->getModule(),$router->getAction()),$params);
    }

    /**
     * 生成router对象
     * @return Router
     */
    public function createRouter()
    {
        $router = new WebRouter();
        $this->router = $router;
        return $this->router;
    }





} 