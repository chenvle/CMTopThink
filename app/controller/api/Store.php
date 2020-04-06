<?php
namespace app\controller\api;

use app\BaseController;
use app\middleware\Auth;
use app\Request;
use think\App;

use app\model\Role as RoleModel;
use app\model\Store as StoreModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Store extends BaseController
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

    public function getStoreData(Request $request)
    {
        try {
            $limit = $request->param('limit');
            $key = $request->param('key');
            $service = (new StoreModel);
            if($key){
                $service = $service->where('name','like','%'.$key.'%');
            }
            if(!is_Admin()){
                $service = $service->where('user_id',Auth()->id);
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

        if(!$name || strlen($name)<4 || strlen($name)>25){
            return msg_error('名称必填,且长度大于4并小于25');
        }
        $data = [
            'name' => $name,
            'user_id' => Auth()->id,
        ];
        $service_id = (new StoreModel)->insertGetId($data);
        if($service_id){
            return msg_success('操作成功',$service_id);
        }else{
            return msg_error('操作失败');
        }
    }

    public function update(Request $request)
    {
        $name = $request->param('name');
        $id = $request->param('id');


        try {
            if(!$name || strlen($name)<4 || strlen($name)>25){
                return msg_error('名称必填,且长度大于4并小于25');
            }
            $service = (new StoreModel)->findOrFail($id);
        } catch (DataNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常',$e);
        }


        $data = [
            'name' => $name,
            'user_id' => Auth()->id,
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
            $role = (new StoreModel)->find($id);
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

}
