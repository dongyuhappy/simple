<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

use \Simple\Bootstrap\Bootstrap;

define('DS',DIRECTORY_SEPARATOR);

//网站根目录
define('ROOT',dirname(dirname(__FILE__)));

//框架的路径
define('SIMPLE_LIB_PATH',ROOT.DS.'simple_master'.DS.'src'.DS);

//项目的路径
define('APP_PATH',ROOT.DS.'simple_master'.DS.'build'.DS.'src'.DS);

//定义项目的顶级命名空间
define('APP_TOP_NAMESPACE','Build');

//项目配置文件
$configPath = APP_PATH.DS.'Build'.DS.'Config'.DS.'config.php';
$config = require($configPath);

//加载框架入口文件
require SIMPLE_LIB_PATH.'sm.php';

//初始化
Bootstrap::init();

//项目的app对象
$app = new \Build\Cycle\BuildApplication($config);

//启动服务
Bootstrap::start($app);






 