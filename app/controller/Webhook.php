<?php
namespace app\controller;

use app\BaseController;

class Webhook extends BaseController
{
    public function index()
    {
      //本地路径
      $local = '/www/wwwroot/CMtime_system';
      //仓库地址
      $remote = 'git@e.coding.net:chenvle/CMtime_system.git';
      //密码
      $password = 'yb621168';

      echo 'develop:';
      echo shell_exec("cd {$local} && git pull {$remote} 2>&1");
      die('done ' . date('Y-m-d H:i:s', time()));
    }
}
