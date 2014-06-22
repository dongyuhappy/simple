<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Cycle;


use Simple\Config\ConfigManager;
use Simple\Text\String;

class Router
{

    /**
     * 当前请求模块的名称
     * @var string
     */
    private $module = null;


    /**
     *  当前请求操作的名称
     * @var string
     */
    private $action = null;


    /**
     * 当前操作的组
     * @var string
     */
    private $group = null;


    public function __construct(){
        $this->_process();
    }



    public function check($name){
        if(empty($name))
            return false;
        return preg_match('/^[A-Za-z](\/|\w)*$/',$name) > 0;
    }


    /**
     * 处理路由信息
     *
     */
    protected  function _process(){
        $moduleVar = ConfigManager::get('module_var');
        $actionVar = ConfigManager::get('action_var');
        $groupVar = ConfigManager::get('group_var');

        //模块
        if(isset($_GET[$moduleVar])){
            $this->module = String::processMethodName($_GET[$moduleVar]);
            unset($_GET[$moduleVar]);
        }else if(isset($_POST[$moduleVar])){
            $this->module = String::processMethodName($_POST[$moduleVar]);
            unset($_POST[$moduleVar]);
        }
        if(!$this->check($this->module)){
            $this->module = String::processMethodName(ConfigManager::get('default_module'));
        }

        //操作
        if(isset($_GET[$actionVar])){
            $this->action = String::processMethodName($_GET[$actionVar]);
            unset($_GET[$actionVar]);
        }else if(isset($_POST[$actionVar])){
            $this->action = String::processMethodName($_POST[$actionVar]);
            unset($_POST[$actionVar]);
        }

        if(!$this->check($this->action)){
            $this->action = String::processMethodName(ConfigManager::get('default_action'));
        }



        //组
        if(isset($_GET[$groupVar])){
            $this->group = String::processMethodName($_GET[$groupVar]);
            unset($_GET[$groupVar]);
        }else if(isset($_POST[$groupVar])){
            $this->group = String::processMethodName($_POST[$groupVar]);
            unset($_POST[$groupVar]);
        }

        if(!$this->check($this->group)){
            $this->group = null;
        }

    }

    /**
     * 获取具体的操作
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * 获取组
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * 获取模块
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }


} 