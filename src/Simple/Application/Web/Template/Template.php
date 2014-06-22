<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Web\Template;


use Simple\Application\Web\Exception\TemplateException;
use Simple\Application\Web\WebResponse;
use Simple\Config\ConfigManager;

/**
 * 模板相关的操作
 * Class Template
 * @package Simple\Application\Web\Template
 */
class Template {

    /**
     * 加载模板文件
     * @param WebResponse $response
     * @param string $tpl 要加载的模板，默认为null，根据header加载
     * @throws TemplateException
     */
    public static function loadTemplate(WebResponse $response,$tpl = null){
        $tplPath = ConfigManager::get('tpl');//.implode(DIRECTORY_SEPARATOR,$header);
        if($tpl){
            $tplPath =  $tplPath.$tpl.'.php';
        }else{
            $header = $response->getHeader();
            $tplPath = $tplPath.implode(DIRECTORY_SEPARATOR,$header).'.php';
        }
        if(file_exists($tplPath) === false)
            throw new TemplateException('不存在的模板文件:'.$tplPath);
        include $tplPath;
    }
} 