<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Web;



use Simple\Cycle\Request;
use Simple\Debug\Debug;

class WebRequest extends Request{
    /**
     * 来源
     * @var string
     */
    private $referer = null;

    /**
     * 请求的时间
     * @var int
     */
    private $requestTime = 0;


    /**
     * 请求的http方法
     * @var string
     */
    private $requestMethod = null;

    public function __construct($header,$body){
        parent::__construct($header,$body);
        if(isset($_SERVER['HTTP_REFERER'])){
            $this->referer = $_SERVER['HTTP_REFERER'];
        }

        $this->requestTime = $_SERVER['REQUEST_TIME'];
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];


        $scriptName = $_SERVER['SCRIPT_NAME'];
        $root = dirname($scriptName);
        $header = $this->getHeader();

        //定义请求相关常量
        define('_ROOT_',$root);
        define('_APP_',$scriptName);
        define('_MODULE_',$header[0]);
        define('_ACTION_',$header[1]);
        define('_TIME_',$_SERVER['REQUEST_TIME']);


    }

    /**
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return int
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }



    /**
     * 验证
     * @return mixed
     */
    public function auth()
    {

    }

} 