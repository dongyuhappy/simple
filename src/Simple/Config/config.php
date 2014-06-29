<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * 系统核心配置
 *
 */
return array(

    'module_var' =>'m',//调用的模块
    'action_var' =>'a',//操作的名称
    'group_var' =>'g',//组

    'default_module'=>'Index',//默认调用的模块
    'default_action'=>'Index',//默认调用的操作
    'module_namespace' =>'Controller',//项目module的命名空间名称
    'module_suffix' =>'Controller',//模块名称的后缀

    //框架中使用的第三方库的的命名空间
    //使用到得第三方库必须遵守PSR-0
    'simple_vendor' => array(
        'Monolog', //日志库
        'Psr',
    ),

    //项目第三方类库目录
    'app_vendor_path' => 'verdor_apth',
    //项目第三方类库的包含的命名空间
    'app_vendor' => array(),

    'timezone' => 'Asia/Hong_Kong', //默认的时区
    'log' => dirname(APP_PATH) . DIRECTORY_SEPARATOR . 'log', //日志文件的跟目录

    'debug'=>true,//默认为调试模式

    //日志输出格式
    'log_formatter'=>"%datetime% > %level_name% > %message%\n",
//        'datetime',//日期(日期的格式化为)
//        'level_name',//级别
//        'message',//日志信息





);
 