<?php
namespace app\index\controller;

use app\BaseController;
use app\Request;

class Index extends BaseController
{
    public function index(Request $request)
    {
        echo 'CMTopThink V6.0';
    }
}
