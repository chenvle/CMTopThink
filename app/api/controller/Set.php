<?php


namespace app\api\controller;


use app\ApiBaseController;
use app\common\model\Set as SetModel;
use app\Request;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Request as RequestFacade;

class Set extends ApiBaseController
{

    public function update(Request $request)
    {

        /**
         * Api认证
         */
        $api_auth = api_auth($this->token);
        if (!$api_auth['status']) {
            return json($api_auth);
        }

        $data = input();

        foreach ($data as $key => $value) {
            $set = SetModel::where('name',$key)->find();
            $set->save(['value'=>$value]);
        }
        return msg_success_api();

    }
}