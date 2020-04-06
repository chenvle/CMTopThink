<?php
namespace app\admin\controller\api;

use app\BaseController;
use app\middleware\Auth;
use app\Request;
use think\App;

use app\common\model\Role as RoleModel;
use app\common\model\User as UserModel;
use app\common\model\Commission as CommissionModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Commission extends BaseController
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

    public function getCommissionData(Request $request)
    {
        try {
            $limit = $request->param('limit');
            $key = $request->param('key');
            $service = (new CommissionModel);
            if($key){
                $service = $service->where('name','like','%'.$key.'%');
            }
            $info = $service->order('id','desc')->paginate($limit);
            return msg_success('ok',$info);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }
    }
    public function store(Request $request)
    {
        $start = $request->param('start');
        $end = $request->param('end');
        $commission = $request->param('commission');

        if((!$start && $start !== '0') || !$end || !$commission){
            return msg_error('请填写完整');
        }


        $info = $this->check_price($start,$end);

        if(!$info['status']){
            return $info;
        }

        $data = [
            'start' => $start,
            'end' => $end,
            'commission' => $commission,
        ];
        $service_id = (new CommissionModel)->insertGetId($data);
        if($service_id){
            return msg_success('操作成功');
        }else{
            return msg_error('操作失败');
        }
    }

    public function update(Request $request)
    {
        $start = $request->param('start');
        $end = $request->param('end');
        $commission = $request->param('commission');
        $id = $request->param('id');

        if((!$start && $start !== '0') || !$end || !$commission){
            return msg_error('请填写完整');
        }

        try {
            $commission = (new CommissionModel)->findOrFail($id);
        } catch (DataNotFoundException $e) {
            return msg_error();
        } catch (ModelNotFoundException $e) {
            return msg_error();
        }

        $info = $this->check_price($start,$end,$id);

        if(!$info['status']){
            return $info;
        }

        $data = [
            'start' => $start,
            'end' => $end,
            'commission' => $commission,
        ];

        $info = $commission->save($data);
        if($info){
            return msg_success('操作成功',$info);
        }else{
            return msg_error('操作失败',$info);
        }
    }

    protected function check_price($start,$end,$id=false)
    {
        try {
            if($id){
                $start_check = (new CommissionModel)->where('start', '<=', $start)->where('end','>',$start)->where('id','<>',$id)->select();
                $end_check = (new CommissionModel)->where('start', '<=', $end)->where('end', '>',$end)->where('id','<>',$id)->select();
            }else{
                $start_check = (new CommissionModel)->where('start', '<=', $start)->where('end','>',$start)->select();
                $end_check = (new CommissionModel)->where('start', '<=', $end)->where('end', '>',$end)->select();
            }

        } catch (DataNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }

        if(count($start_check) > 0 || count($end_check) > 0){
            return msg_error('范围错误');
        }else{
            return msg_success();
        }
    }

    public function del(Request $request)
    {
        $id = $request->param('id');
        try {
            $role = (new CommissionModel)->find($id);
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
    public function get(Request $request)
    {
        $price = $request->param('price');
        try {
            $commission = (new CommissionModel)->where('start', '<=', $price)->where('end', '>',$price)->select();
            if(count($commission)){
                return msg_success('ok',$commission[0]->commission);
            }else{
                return msg_error();
            }
        } catch (DataNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }

    }

}
