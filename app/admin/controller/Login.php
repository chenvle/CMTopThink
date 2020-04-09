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
        $type = $request->param('type');
        if($type == 'admin'){
            return view('index',['Message'=>get_message(),'is_admin'=>'','title'=>'CMtime晨明网络']);
        }else{
            return view('index',['Message'=>get_message(),'is_admin'=>'user_login','title'=>'外贸管理系统']);
        }
    }
}
