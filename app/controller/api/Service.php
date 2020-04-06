<?php
namespace app\controller\api;

use app\BaseController;
use app\middleware\Auth;
use app\Request;
use think\App;

use app\model\Role as RoleModel;
use app\model\User as UserModel;
use app\model\Service as ServiceModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Service extends BaseController
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

    public function getServiceData(Request $request)
    {
        try {
            $limit = $request->param('limit');
            $key = $request->param('key');
            $service = (new ServiceModel);
            if($key){
                $service = $service->where('name','like','%'.$key.'%');
            }
            if (!is_Admin()) {
                $service = $service->where('display',1);
            }
            $info = $service->order('id','desc')->paginate($limit);
            return msg_success('ok',$info);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }
    }
    public function store(Request $request)
    {
        $name = $request->param('name');
        $display = $request->param('display');
        $qq = $request->param('qq');
        $qr = $request->param('qr');
        $phone = $request->param('phone');

        if(!$name || strlen($name)<4 || strlen($name)>25){
            return msg_error('名称必填,且长度大于4并小于25');
        }
        if($display=='on'){
            $display = true;
        }else{
            $display = false;
        }

        $data = [
            'name' => $name,
            'display' => $display,
            'qq' => $qq,
            'qr' => $qr,
            'phone' => $phone,
        ];
        $service_id = (new ServiceModel)->insertGetId($data);
        if($service_id){
            return msg_success('操作成功',$service_id);
        }else{
            return msg_error('操作失败');
        }
    }

    public function update(Request $request)
    {
        $name = $request->param('name');
        $display = $request->param('display');
        $qq = $request->param('qq');
        $qr = $request->param('qr');
        $phone = $request->param('phone');
        $id = $request->param('id');


        try {
            if(!$name || strlen($name)<4 || strlen($name)>25){
                return msg_error('名称必填,且长度大于4并小于25');
            }
            $service = (new ServiceModel)->findOrFail($id);
        } catch (DataNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常',$e);
        }


        if($display=='on'){
            $display = true;
        }else{
            $display = false;
        }

        $data = [
            'name' => $name,
            'display' => $display,
            'qq' => $qq,
            'qr' => $qr,
            'phone' => $phone,
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
            $role = (new ServiceModel)->find($id);
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
            $permissions = (new ServiceModel)->order('name','asc')->select();
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
