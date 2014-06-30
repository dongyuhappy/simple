<?php
/**
 * @link http://www.simple-inc.cn/
 * @copyright Copyright (c) 2014 Simple-inc Software  inc
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Build\Controller;



use Build\Cycle\BuildRequest;
use Build\Cycle\BuildResponse;

class IndexController {


    public function index(BuildRequest $request){
        return new BuildResponse();
    }


    public function login(BuildRequest $request){

        return new BuildResponse();
    }


} 