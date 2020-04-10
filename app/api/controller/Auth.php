<?php


namespace app\api\controller;

use app\BaseController;
use app\Request;
use app\api\middleware\Api;

class Auth extends BaseController
{
    /*
     * 中间件
     *
     * */
    protected $middleware = [Api::class];
    public function login(Request $request)
    {
        return 'ok';
    }
}