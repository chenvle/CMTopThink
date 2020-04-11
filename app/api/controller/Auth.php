<?php


namespace app\api\controller;

use app\BaseController;
use app\Request;

class Auth extends BaseController
{
    /*
     * 中间件
     *
     * */
    protected $middleware = [];
    public function login(Request $request)
    {

        $info = Response()->getHeader();
        dump($info);
        return 'ok';
    }
}