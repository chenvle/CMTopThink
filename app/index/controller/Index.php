<?php
namespace app\index\controller;

use app\BaseController;
use app\middleware\Auth;
use app\Request;

class Index extends BaseController
{
    /*
    * 中间件
    *
    * */
    protected $middleware = [Auth::class];

    public function index(Request $request)
    {
        echo 'CMTopThink V6.0';
    }
}
