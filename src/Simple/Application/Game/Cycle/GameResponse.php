<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Simple\Application\Game\Cycle;


use Simple\Config\ConfigManager;
use Simple\Cycle\Response;

class GameResponse extends Response
{

    /**
     * 把数据返回给客户端
     * @return string
     */
    function toClient()
    {
        $data = array();
        $data['stat'] = $this->getStatus();
        $data['data'] = $this->getBody();
        $header = $this->getHeader();
        $data['head'][ConfigManager::get('module_var')] = $header[0];
        $data['head'][ConfigManager::get('action_var')] = $header[1];
        if(isset($header[2])){
            $data['head'][ConfigManager::get('group_var')] = $header[2];
        }
        return $data;
    }

} 