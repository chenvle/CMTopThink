<?php
namespace app\admin\controller;

use app\BaseController;
use app\middleware\Auth;
use app\Request;

class Login extends BaseController
{
    /*
    * 中间件
    *
    * */
    protected $middleware = [Auth::class];
    public function index(Request $request)
    {
        return view('index',['Message'=>get_message(),'is_admin'=>'','title'=>'CMtime晨明网络']);
    }
}
