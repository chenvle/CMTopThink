<?php

namespace app\admin\controller;

use app\BaseController;
use app\middleware\Auth;
use app\common\model\Role as RoleModel;
use app\common\model\User as UserModel;
use app\common\model\Permission as PermissionModel;
use app\Request;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class User extends BaseController
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

    public function admin()
    {
        return view();
    }

    public function create(Request $request)
    {
        $type = $request->param('type');
        try {
            $admin_roles = (new RoleModel)->where('type', $type)->select();
        } catch (DataNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (DbException $e) {
            return msg_error('异常', $e);
        }
        return view('create', ['roles' => $admin_roles]);
    }

    public function edit(Request $request)
    {
        try {
            $user_id = $request->param('id');
            $type    = $request->param('type');
            $user    = (new UserModel)->with('roles')->findOrEmpty($user_id);
            if (!$user) {
                return dump($user);
            }
            $admin_roles = (new RoleModel)->where('type', $type)->select();
        } catch (DataNotFoundException $e) {
            return dump($e);
        } catch (ModelNotFoundException $e) {
            return dump($e);
        } catch (DbException $e) {
            return dump($e);
        }
        return view('edit', ['roles' => $admin_roles, 'info' => $user]);
    }

}
