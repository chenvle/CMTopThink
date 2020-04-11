<?php


namespace app\admin\controller;


use app\BaseController;
use app\middleware\Auth;
use app\common\model\Account as AccountModel;
use app\common\model\AccountGroup as AccountGroupModel;
use app\Request;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Account extends BaseController
{
    /*
         * 中间件
         *
         * */
    protected $middleware = [];

    public function index()
    {
        return view();
    }

    public function create()
    {
        try {
            $groups = (new AccountGroupModel)->select();
        } catch (DataNotFoundException $e) {
            dump($e);
        } catch (ModelNotFoundException $e) {
            dump($e);
        } catch (DbException $e) {
            dump($e);
        }
        return view('create', ['groups' => $groups]);
    }

    public function group()
    {
        return view();
    }

    public function edit(Request $request)
    {
        try {
            $service_id = $request->param('id');
            $service    = (new AccountModel)->findOrEmpty($service_id);
            $groups     = (new AccountGroupModel)->select();
        } catch (DataNotFoundException $e) {
            return dump($e);
        } catch (ModelNotFoundException $e) {
            return dump($e);
        } catch (DbException $e) {
            return dump($e);
        }
        return view('edit', ['info' => $service, 'groups' => $groups]);
    }
}