<?php


namespace app\controller;


use app\BaseController;
use app\middleware\Auth;
use app\model\Role as RoleModel;
use app\model\User as UserModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;

class Role extends BaseController
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
    public function power(Request $request)
    {
        try {
            $role_id = $request->param('id');
            $role = (new RoleModel())->findOrEmpty($role_id);
        } catch (DataNotFoundException $e) {
            return dump($e);
        } catch (ModelNotFoundException $e) {
            return dump($e);
        } catch (DbException $e) {
            return dump($e);
        }

        return view('power',['info'=>$role]);
    }
    public function edit(Request $request)
    {
        try {
            $role_id = $request->param('id');
            $role = (new RoleModel())->findOrEmpty($role_id);
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