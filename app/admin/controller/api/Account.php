<?php
namespace app\admin\controller\api;

use app\BaseController;
use app\middleware\Auth;
use app\Request;
use think\App;

use app\common\model\Role as RoleModel;
use app\common\model\User as UserModel;
use app\common\model\Account as AccountModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Account extends BaseController
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

    public function getAccountData(Request $request)
    {
        try {
            $limit = $request->param('limit');
            $key = $request->param('key');
            $account = (new AccountModel);
            if($key){
                $account = $account->where('name','like','%'.$key.'%');
            }
            $info = $account->with('account_group')->order('id','desc')->paginate($limit);
            return msg_success('ok',$info);
        } catch (DbException $e) {
            dump($e);
//            return msg_error('异常',$e);
        }
    }
    public function store(Request $request)
    {
        $name = $request->param('name');
        $group = $request->param('group');

        if(!$name || strlen($name)<4 || strlen($name)>25){
            return msg_error('名称必填,且长度大于4并小于25');
        }


        $data = [
            'name' => $name,
            'account_group_id' => $group,
        ];
        $account_id = (new AccountModel)->insertGetId($data);
        if($account_id){
            return msg_success('操作成功',$account_id);
        }else{
            return msg_error('操作失败');
        }
    }

    public function update(Request $request)
    {
        $name = $request->param('name');
        $group = $request->param('group');
        $id = $request->param('id');


        try {
            if(!$name || strlen($name)<4 || strlen($name)>25){
                return msg_error('名称必填,且长度大于4并小于25');
            }
            $service = (new AccountModel)->findOrFail($id);
        } catch (DataNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常',$e);
        }


        $data = [
            'name' => $name,
            'account_group_id' => $group,
        ];

        $info = $service->save($data);
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
            $role = (new AccountModel)->find($id);
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
    public function list(Request $request)
    {
        $id = $request->param('id');
        try {
            $permissions = (new AccountModel)->order('name','asc')->select();
            $role = (new RoleModel)->findOrFail($id);
            if(!$role){
                return msg_error();
            }
            $permissions_ids = array_column($role->permissions->toArray(),'id');
            $list = [];
            foreach ($permissions as $permission) {
                if(in_array($permission->id,$permissions_ids)){
                    $list[] = [
                        'id'=>$permission->id,
                        'title'=>$permission->name,
                        'checked'=>true
                    ];
                }else{
                    $list[] = [
                        'id'=>$permission->id,
                        'title'=>$permission->name
                    ];
                }
            }
            return msg_success('ok',$list);
        } catch (DataNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }

    }

}
