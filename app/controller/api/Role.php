<?php
namespace app\controller\api;

use app\BaseController;
use app\middleware\Auth;
use app\Request;
use think\App;

use app\model\Role as RoleModel;
use app\model\User as UserModel;
use app\model\Permission as PermissionModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Role extends BaseController
{

    /*
    * 中间件
    *
    * */
    protected $middleware = [Auth::class];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function getRoleData(Request $request)
    {
        try {
            $limit = $request->param('limit');
            $key = $request->param('key');
            $roles = (new RoleModel);
            if($key){
                $roles = $roles->where('name','like','%'.$key.'%');
            }
            $info = $roles->paginate($limit);
            return msg_success('ok',$info);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }
    }
    public function store(Request $request)
    {
        $name = $request->param('name');
        $type = $request->param('type');
        if(!$type){
            return msg_error('请选择类型');
        }
        if(!$name || strlen($name)<6 || strlen($name)>25){
            return msg_error('名称必填,且长度大于4并小于25');
        }

        $data = [
            'name' => $name,
            'type' => $type,
        ];
        $role_id = (new RoleModel)->insertGetId($data);
        if($role_id){
            return msg_success('操作成功',$role_id);
        }else{
            return msg_error('操作失败');
        }
    }

    public function update(Request $request)
    {
        $name = $request->param('name');
        $type = $request->param('type');
        $id = $request->param('id');

        try {
            $role = (new RoleModel)->findOrFail($id);
        } catch (DataNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常',$e);

        }


        if(!$type){
            return msg_error('类型至少选一个');
        }
        if(!$name || strlen($name)<6 || strlen($name)>25){
            return msg_error('名称必填,且长度大于4并小于25');
        }

        $data = [
            'name' => $name,
            'type' => $type,
        ];

        $info = $role->save($data);
        if($info){
            return msg_success('操作成功',$info);
        }else{
            return msg_error('操作失败',$info);
        }
    }
    public function del(Request $request)
    {
        $id = $request->param('id');
        try {
            $role = (new RoleModel)->find($id);
            $role->delete();
            return msg_success();
        } catch (DataNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }
    }
    public function power(Request $request)
    {
        try{
            if($this->request->isPost()){
                $role_id = $this->request->param('id');
                $role = (new RoleModel)->findOrFail($role_id);

                $permissions = $request->param('permissions');
                $permission_ids = array_column($permissions,'id');
                $role->permissions()->sync($permission_ids);
                return msg_success();
            }else{
                return msg_error('Please use POST type!');
            }
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }
    }

}
