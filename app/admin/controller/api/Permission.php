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

class Permission extends BaseController
{
    /*
    * 中间件
    *
    * */
    protected $middleware = [];

    public function __construct(App $app)
    {

        parent::__construct($app);
    }

    public function getRoleData(Request $request)
    {
        try {
            $limit       = $request->param('limit');
            $key         = $request->param('key');
            $permissions = (new PermissionModel);
            if ($key) {
                $permissions = $permissions->where('name', 'like', '%' . $key . '%');
            }
            $info = $permissions->order('id', 'desc')->paginate($limit);
            return msg_success('ok', $info);
        } catch (DbException $e) {
            return msg_error('异常', $e);
        }
    }

    public function store(Request $request)
    {
        $name         = $request->param('name');
        $display_name = $request->param('display_name');
        $type         = $request->param('type');

        if (!$name || strlen($name) < 4 || strlen($name) > 25) {
            return msg_error('权限路由必填,且长度大于4并小于25');
        }
        if (!$display_name || strlen($display_name) < 4 || strlen($display_name) > 25) {
            return msg_error('名称必填,且长度大于4并小于25');
        }

        $data          = [
            'name'         => $name,
            'display_name' => $display_name,
            'type'         => $type,
        ];
        $permission_id = (new PermissionModel)->insertGetId($data);
        if ($permission_id) {
            return msg_success('操作成功', $permission_id);
        } else {
            return msg_error('操作失败');
        }
    }

    public function update(Request $request)
    {
        $display_name = $request->param('display_name');
        $name         = $request->param('name');
        $type         = $request->param('type');
        $id           = $request->param('id');

        try {
            $permission = (new PermissionModel)->findOrFail($id);
        } catch (DataNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常', $e);
        }

        if (!$display_name || strlen($display_name) < 4 || strlen($display_name) > 25) {
            return msg_error('名称必填,且长度大于4并小于25');
        }

        $data = [
            'display_name' => $display_name,
            'name'         => $name,
            'type'         => $type,
        ];

        $info = $permission->save($data);
        if ($info) {
            return msg_success('操作成功', $info);
        } else {
            return msg_error('操作失败', $info);
        }
    }

    public function del(Request $request)
    {
        $id = $request->param('id');
        try {
            $role = (new PermissionModel)->find($id);
            $role->delete();
            return msg_success();
        } catch (DataNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (DbException $e) {
            return msg_error('异常', $e);
        }
    }

    public function list(Request $request)
    {
        $id = $request->param('id');
        try {
            $role = (new RoleModel)->findOrFail($id);

            if ($role->type == 'admin') {
                $permissions = (new PermissionModel)->where('type', 'admin')->order('name', 'asc')->select();
            } else {
                $permissions = (new PermissionModel)->where('type', 'user')->order('name', 'asc')->select();
            }


            if (!$role) {
                return msg_error();
            }

            $role_permissions = $role->permissions->toArray();
            $permissions_ids  = array_column($role_permissions, 'id');
            $list             = [];
            foreach ($permissions as $permission) {
                if (in_array($permission->id, $permissions_ids)) {
                    $list[] = [
                        'id'      => $permission->id,
                        'title'   => $permission->display_name,
                        'checked' => true
                    ];
                } else {
                    $list[] = [
                        'id'    => $permission->id,
                        'title' => $permission->display_name
                    ];
                }
            }
            return msg_success('ok', $list);
        } catch (DataNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (DbException $e) {
            return msg_error('异常', $e);
        }

    }

}
