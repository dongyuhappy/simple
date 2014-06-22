<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Exception;


use Exception;
use Monolog\Logger;
use Simple\Log\LogUtil;

class SimpleException extends \Exception
{

    /**
     * 构造方法
     * @param string $message
     * @param int $code
     * @param array $info
     */
    public function __construct($message = "", $code = 0, $info = array())
    {
        parent::__construct($message, $code);
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $trace['message'] = $message;
        $trace['code'] = $code;
        LogUtil::write($trace, str_replace('\\', '_', get_class($this)), Logger::ERROR);


    }


} 