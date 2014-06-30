<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Build\Cycle;



use Build\Exception\BuildException;
use Simple\Application\Web\WebApplication;
use Simple\Config\ConfigManager;
use Simple\Cycle\Router;
use Simple\Log\LogUtil;

class BuildApplication extends WebApplication{


    /**
     * @return \Simple\Application\Web\WebResponse|void
     */
    public function run()
    {

        $resp = null;
        try {
            $resp = parent::run();
        } catch (\Exception $e) {
            //跳转到设置的默认页面
//            URL::redirect(URL::U('system', 'exception'));
            echo $e->getMessage();
            exit;
        }

        try {
            $resp->toClient();
        } catch (\Exception $e) {
            //捕捉模板抛出的错误
            LogUtil::write($e->getMessage(), 'tpl_error');
            header("Charset:utf-8");
            header("X-Powered-By:simple");
            if (ConfigManager::get('debug')) {
                echo "渲染模板出错：" . $e->getMessage();
            } else {
                echo "系统内部错误。";
            }
        }
    }


    /**
     * 获取关系型数据库的连接配置信息
     * @param $name
     * @return mixed
     */
    public function getDbConfig($name)
    {
        return $this->getDatabaseConfig($name, 1);
    }


    /**
     * 获取数据库配置信息
     * @param string $name
     * @param int $type 数据库类型 MySQL(1) NoSQL(2)
     * @return array
     * @throws BuildException
     */

    private function getDatabaseConfig($name, $type)
    {
        if ($type == 1) {
            //MySQL
            $cnf = ConfigManager::get('mysql');
        } else if ($type == 2) {
            //Mem
            $cnf = ConfigManager::get('mem');
        } else {
            throw new BuildException('unknown type' . $type);
        }
        if (isset($cnf[$name]) == false)
            throw new BuildException('找不到name为:' . $name . '的配置信息。');
        return $cnf[$name];
    }

    /**
     * 获取NoSQL连接配置信息
     * @param $name
     * @return array
     * @throws BuildException
     */
    public function getNoSQLConfig($name)
    {
        return $this->getDatabaseConfig($name, 2);
    }

    public function createRequest(Router $router)
    {
        $params = array_merge($_GET,$_POST);
        $this->request = new BuildRequest($params);
    }


} 