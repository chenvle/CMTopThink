<?php


namespace app\api\controller;

use app\BaseController;

class Webhook extends BaseController
{
    public function pull()
    {

        //本地路径
        $local = '/www/wwwroot/CMTopThink';
        //仓库地址
        $remote = 'git@github.com:chenvle/CMTopThink.git';


        echo 'develop:';
        echo shell_exec("cd {$local} && git pull {$remote} 2>&1");
        die('done ' . date('Y-m-d H:i:s', time()));
    }
}