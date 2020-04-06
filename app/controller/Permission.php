<?php

namespace app\controller;

use app\BaseController;
use app\middleware\Auth;
use app\model\Permission as PermissionModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;

class Permission extends BaseController
{
    /*
         * 中间件
         *
         * */
    protected $middleware = [Auth::class];

    public function index()
    {
        return view();
    }
    public function create()
    {
        return view();
    }
    public function edit(Request $request)
    {
        try {
            $role_id = $request->param('id');
            $role = (new PermissionModel)->findOrEmpty($role_id);
        } catch (DataNotFoundException $e) {
            return dump($e);
        } catch (ModelNotFoundException $e) {
            return dump($e);
        } catch (DbException $e) {
            return dump($e);
        }
        return view('edit',['info'=>$role]);
    }
}