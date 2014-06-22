<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Model\Driver;


class MySQLPDO extends \PDO
{
    /**
     * 连接信息，也作为对象的一个标识返回
     * @var string
     */
    private $_dsn;


    /**
     *
     * @param string $host 数据库地址
     * @param int $port 端口
     * @param string $username 帐号
     * @param string $passwd 密码
     * @param string $database 数据库
     * @param string $charset 编码，默认为utf8,也建议使用utf8
     */
    public function __construct($host, $port, $username, $passwd, $database, $charset = "utf8")
    {
        $dsn = 'mysql:dbname=' . $database . ';host=' . $host . ';port=' . $port;
        $this->_dsn = $dsn;
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'set names ' . $charset, //设置编码
            \PDO::ATTR_CASE => \PDO::CASE_LOWER, //所有的字段都为小写
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, //所有的错误都由Exception形式报告
            \PDO::ATTR_TIMEOUT => 30, //设置超时时间30秒
            \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE, //使用MySQL的查询缓存
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, //数据以关联数据的形式返回
        );
        parent::__construct($dsn, $username, $passwd, $options);
    }


    /**
     * 不解释，你懂的，什么？不懂？翻手册！
     * @return string
     */
    public function __toString()
    {
        return $this->_dsn;
    }
} 