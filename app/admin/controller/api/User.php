<?php

namespace app\admin\controller\api;

use app\BaseController;
use app\middleware\Auth;
use app\Request;
use think\App;

use app\common\model\Role as RoleModel;
use app\common\model\User as UserModel;
use app\common\model\Permission as PermissionModel;
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
    protected $user_role_ids;
    protected $admin_role_ids;
    protected $users;

    public function __construct(App $app)
    {
        try {
            $this->user_role_ids  = [];
            $this->admin_role_ids = [];
            $user_roles           = (new RoleModel)->where(['type' => 'user'])->select();
            if (count($user_roles)) {
                $this->user_role_ids = array_column($user_roles->toArray(), 'id');
            }
            $admin_roles = (new RoleModel)->where(['type' => 'admin'])->select();
            if (count($admin_roles)) {
                $this->admin_role_ids = array_column($admin_roles->toArray(), 'id');
            }
            $this->users = (new UserModel)->select();

        } catch (DataNotFoundException $e) {
            return $e;
        } catch (ModelNotFoundException $e) {
            return $e;
        } catch (DbException $e) {
            return $e;
        }
        parent::__construct($app);
    }

    public function getUserData(Request $request)
    {
        try {
            $type = $request->param('type');
            $key  = $request->param('key');
            if ($type == 'admin') {
                $user_ids = $this->admin_role_ids;
            } elseif ($type == 'user') {
                $user_ids = $this->user_role_ids;
            } else {
                return msg_error();
            }
            $users = $this->users->filter(function ($item) use ($user_ids, $key) {
                $roles = $item->roles->toArray();
                $ids   = array_column($roles, 'id');
                foreach ($ids as $index => $id) {
                    if (in_array($id, $user_ids)) {
                        if ($key) {
                            if (strpos($item->name, $key) !== false) {
                                $item->role_name = implode('、', array_column($roles, 'name'));
                                return $item;
                            } else {
                                return false;
                            }
                        } else {
                            $item->role_name = implode('、', array_column($roles, 'name'));
                            return $item;
                        }
                    }
                }
                return false;
            });
            return msg_success('ok', $users);
        } catch (DbException $e) {
            return msg_error('异常', $e);
        }
    }

    public function store(Request $request)
    {
        $roles    = $request->param('Roles');
        $username = $request->param('username');
        $password = $request->param('password');
        $name     = $request->param('name');
        $Sex      = $request->param('Sex');
        if (!$roles) {
            return msg_error('角色至少选一个');
        }
        if (!$username || strlen($username) < 6 || strlen($username) > 25) {
            return msg_error('账号必填,且长度大于6并小于25');
        }
        if (!$password || strlen($password) < 6 || strlen($password) > 25) {
            return msg_error('密码必填,且长度大于6并小于25');
        }
        if (!$name) {
            return msg_error('昵称必填');
        }

        $data    = [
            'username' => $username,
            'name'     => $name,
            'Sex'      => $Sex,
            'password' => makePassword($password),
        ];
        $user_id = (new UserModel)->insertGetId($data);
        if ($user_id) {
            $user     = (new UserModel)->findOrEmpty($user_id);
            $role_ids = array_values($roles);
            $user->roles()->attach($role_ids);
            return msg_success('操作成功', $roles);
        } else {
            return msg_error('操作失败');
        }
    }

    public function update(Request $request)
    {
        $roles    = $request->param('Roles');
        $username = $request->param('username');
        $password = $request->param('password');
        $name     = $request->param('name');
        $Sex      = $request->param('Sex');
        $id       = $request->param('id');

        try {
            $user = (new UserModel)->findOrFail($id);
        } catch (DataNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常', $e);

        }


        if (!$roles) {
            return msg_error('角色至少选一个');
        }
        if (!$username || strlen($username) < 6 || strlen($username) > 25) {
            return msg_error('账号必填,且长度大于6并小于25');
        }
        if (!$id || !$user) {
            return msg_error('用户异常');
        }

        if (($password && strlen($password) < 6) || ($password && strlen($password) > 25)) {
            return msg_error('密码必填,且长度大于6并小于25');
        }
        if (!$name) {
            return msg_error('昵称必填');
        }

        $data = [
            'name' => $name,
            'Sex'  => $Sex,
        ];
        if ($password) {
            $data['password'] = makePassword($password);
        }
        $info = $user->save($data);
        if ($info) {
            $role_ids = array_values($roles);
            $user->roles()->sync($role_ids);
            return msg_success('操作成功', $user);
        } else {
            return msg_error('操作失败', $user);
        }
    }

    public function del(Request $request)
    {
        $id = $request->param('id');
        try {
            $user = (new \app\common\model\User)->find($id);
            $user->delete();
            return msg_success();
        } catch (DataNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (DbException $e) {
            return msg_error('异常', $e);
        }
    }

}
