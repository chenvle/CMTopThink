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
        if(!$api_auth['status']){return $api_auth;}

        $account     = $request->param('account');
        $qr_img      = $request->param('qr_img');
        $account_two = $request->param('account_two');
        $qr_img_two  = $request->param('qr_img_two');
        $rate        = $request->param('rate');

        try {
            $sets = (new SetModel)->select()->toArray();

        } catch (DataNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (DbException $e) {
            return msg_error('异常', $e);
        }

        $set = new SetModel;
        foreach ($sets as $index => $value) {
            if ($value['name'] == 'account') {
                $sets[$index]['value'] = $account;
            }
            if ($value['name'] == 'qr_img') {
                $sets[$index]['value'] = $qr_img;
            }
            if ($value['name'] == 'account_two') {
                $sets[$index]['value'] = $account_two;
            }
            if ($value['name'] == 'qr_img_two') {
                $sets[$index]['value'] = $qr_img_two;
            }
            if ($value['name'] == 'rate') {
                $sets[$index]['value'] = $rate;
            }
        }
        $set->saveAll($sets);
        return msg_success();

    }
}