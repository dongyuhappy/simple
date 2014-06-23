<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */


namespace Simple\Bootstrap;


use Monolog\Logger;
use Simple\Bootstrap\Exception\BootstrapException;
use Simple\Config\ConfigManager;
use Simple\Cycle\Application;
use Simple\Log\LogUtil;

class Bootstrap
{

    /**
     * app的相关信息
     * @var Application
     */
    private static $_app = null;


    /**
     * 获取正在运行的项目
     *
     * @return Application
     */
    public static function getApp()
    {
        return self::$_app;
    }


    /**
     * 初始化操作
     */
    public static function init()
    {


        // 设置发生非致命错误的处理
        set_error_handler('Simple\Bootstrap\Bootstrap::handlerNonFatal');

        // 设置对程序中未捕捉异常的处理
        set_exception_handler('Simple\Bootstrap\Bootstrap::handlerException');

        //注册自动加载类方法
        spl_autoload_register('Simple\Bootstrap\Bootstrap::autoload');

        self::sysConst(); //定义系统常量
        self::checkRun(); //检查运行环境

        //加载第三方库
        require_once SIMPLE_LIB_PATH . 'Simple' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';


    }


    /**
     * 检查运行环境
     * @throws Exception\BootstrapException
     */
    private static function checkRun()
    {
        if (defined('APP_PATH') == false)
            throw new BootstrapException('APP_PATH is not defined');
        if (defined('SIMPLE_LIB_PATH') == false)
            throw new BootstrapException('SIMPLE_LIB_PATH is not defined');


        if(version_compare(PHP_VERSION,'5.3.0','<'))
            throw new BootstrapException('PHP版本太低， PHP > 5.3.0 !');
    }


    /**
     *
     * 定义系统常量
     */
    private static function sysConst()
    {

        //版本定义
        define('VERSION', '0.1-alpha');

    }


    /**
     * 引导启动服务
     * @param Application $app
     * @throws Exception\BootstrapException
     */
    public static function start(Application $app)
    {


        self::$_app = $app;

        //设置时区
        $timeZone = ConfigManager::get('timezone');
        date_default_timezone_set($timeZone);

        self::$_app->run();

    }

    /**
     * 自动加载
     * @param $className
     * @throws Exception\BootstrapException
     */
    public static function autoload($className)
    {

        $className = ltrim($className, '\\');
        $fileName = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        if (empty($namespace))
            throw new BootstrapException($className . 'namespace is global');

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';


        $namespaceInfo = explode('\\', $namespace);
        $topNameSpace = $namespaceInfo[0];
        if (strcmp($topNameSpace, "Simple") == 0) {
            //系统类库
            $fileName = SIMPLE_LIB_PATH . $fileName;
        } elseif (strcmp(APP_TOP_NAMESPACE, $topNameSpace) === 0) {
            //项目类库
            $fileName = str_replace($topNameSpace, '', APP_PATH) . $fileName;
        } else {
            //第三方库
            $simpleVendor = ConfigManager::get('simple_vendor');
            if (in_array($topNameSpace, $simpleVendor)) {
                //系统的第三方库
                $fileName = SIMPLE_LIB_PATH . 'Simple' . DIRECTORY_SEPARATOR . 'Vendor' . DIRECTORY_SEPARATOR . $fileName;
            } elseif (in_array($topNameSpace, ConfigManager::get('app_vendor'))) {
                //项目的第三方类库
                $appVendorPath = ConfigManager::get('app_vendor_path');
                $fileName = APP_PATH . $appVendorPath . DIRECTORY_SEPARATOR . $fileName;
            }

        }
        if (file_exists($fileName) == false) {
            throw new BootstrapException('加载文件' . $fileName . '失败');
        }

        require $fileName;
    }


    /**
     * 非致命错误的处理
     */
    public static function handlerNonFatal($errno, $errstr, $errfile, $errline)
    {
        $info = "##".$errstr."##出现在文件##".$errfile."##的第##".$errline."##行,错误码为:##".$errno."##";
        LogUtil::write($info,'warning',Logger::WARNING);
// 		return true;//标准错误处理程序停止调用

    }

    /**
     * 对未捕捉的异常进行捕捉处理
     * @param \Exception $e
     */
    public static function handlerException(\Exception $e)
    {
        $msg['message'] = $e->getMessage();
        $msg['file'] = $e->getFile().'#'.$e->getLine();
        $msg['exception'] = get_class($e);
        $msg['line'] = __LINE__;
        try{
            LogUtil::write($msg,'uncatch',Logger::ERROR);
        }catch (\Exception $ecp){
           echo '<p style="color:red;"><b>'.$ecp->getMessage().'</b></p>';//$ecp->getMessage();
            exit;
        }
    }


} 