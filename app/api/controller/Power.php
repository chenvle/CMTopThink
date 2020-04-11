<?php


namespace app\api\controller;

use app\BaseController;

class Power extends BaseController
{
    public function index()
    {
        $type = input('type','异常');
        return json(msg_error($type));
    }
}