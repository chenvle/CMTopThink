<?php


namespace app\api\controller;


use app\ApiBaseController;
use think\App;
use think\facade\Request as RequestFacade;

class Index extends ApiBaseController
{

    public function menus()
    {
        /**
         * Api认证
         */
        $api_auth = api_auth($this->token);
        if(!$api_auth['status']){return json($api_auth);}
        $menus = Menus($this->user);
        return msg_success_api('ok',$menus);

    }
}