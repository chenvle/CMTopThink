<?php


namespace app\api\controller;

use app\BaseController;

class Webhook extends BaseController
{
    public function pull()
    {
        error_reporting ( E_ALL );
        $dir = '/www/wwwroot/CMTopThink/';//该目录为git检出目录
        $handle = popen('cd '.$dir.' && git pull 2>&1','r');
        $read = stream_get_contents($handle);
        printf($read);
        pclose($handle);
    }
}