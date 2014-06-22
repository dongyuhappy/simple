<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Web;

use Simple\Config\ConfigManager;
use Simple\Cycle\Response;

class WebResponse extends Response
{


    /**
     * 把数据返回给客户端
     * @return mixed
     */
    function toClient()
    {
        $this->display(); //渲染模板
    }

    /**
     * 渲染模板
     *
     */
    private function display()
    {
        $header = $this->getHeader();

        //创建loader对象
        $tpl = ConfigManager::get('tpl') . $header[0] . DIRECTORY_SEPARATOR;
        $loader = new \Twig_Loader_Filesystem($tpl);

        $env = $this->makeEnvironment($loader);
        $suffix = ConfigManager::get('tpl_suffix');
        $env->display($header[1] . $suffix, $this->getBody());
    }


    /**
     * 创建模板环境对象
     * @param \Twig_Loader_Filesystem $loader
     * @return \Twig_Environment
     */
    protected function makeEnvironment(\Twig_Loader_Filesystem $loader)
    {

        //配置模板环境选项
        $isDebug = ConfigManager::get('debug');
        $ops = array('debug' => $isDebug);
        if ($isDebug)
            $ops['strict_variables'] = true;
        $twig = new \Twig_Environment($loader, $ops);

        //添加常量
        $this->addGlobal($twig);

        //添加模板方法
        $this->addFunction($twig);

        return $twig;
    }


    /**
     * 新增模板所需要的一些常量
     * @param \Twig_Environment $twig
     */
    protected function addGlobal(\Twig_Environment $twig)
    {

        //添加常用的全局变量
        $twig->addGlobal('__ROOT__', _ROOT_);
        $twig->addGlobal('__APP__', _APP_);
        $twig->addGlobal('__MODULE__', _MODULE_);
        $twig->addGlobal('__ACTION__', _ACTION_);
        $twig->addGlobal('__TIME__', _TIME_);
        $twig->addGlobal('__CSS__', _ROOT_ . '/' . ConfigManager::get('css'));
        $twig->addGlobal('__JS__', _ROOT_ . '/' . ConfigManager::get('js'));
        $twig->addGlobal('__IMAGE__', _ROOT_ . '/' . ConfigManager::get('image'));
    }


    /**
     * 增加模板函数
     * @param \Twig_Environment $twig
     */
    protected function addFunction(\Twig_Environment $twig)
    {

        //url处理
        $url = new \Twig_SimpleFunction('U', array('Simple\Application\Web\Template\TplFunc', 'U'));
        $twig->addFunction($url);

    }


}