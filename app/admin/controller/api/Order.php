<?php
namespace app\admin\controller\api;

use app\BaseController;
use app\admin\controller\Money;
use app\middleware\Auth;
use app\Request;
use think\App;

use app\common\model\Role as RoleModel;
use app\common\model\User as UserModel;
use app\common\model\Order as OrderModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Order extends Money
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

    public function getOrderData(Request $request)
    {
        try {
            $limit = $request->param('limit');
            $key = $request->param('key');
            $type = $request->param('type');
            $order = (new OrderModel);
            if($key){
                $order = $order->where('name','like','%'.$key.'%');
            }
            if(!is_Admin()){
                $order = $order->where('user_id',Auth()->id);
            }
            if($type){
                $order = $order->where('type',$type);
            }
            $info = $order->order('id','desc')->paginate($limit);
            return msg_success('ok',$info);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }
    }
    public function store(Request $request)
    {
        $name = $request->param('name');
        $type = $request->param('type');
        $price = $request->param('price');
        $card_type = $request->param('card_type');
        $number = $request->param('number');
        $remark = $request->param('remark');


        if(!$name || strlen($name)<4 || strlen($name)>25){
            return msg_error('名称必填,且长度大于4并小于25');
        }
        if(!$number){
            return msg_error('流水号/账号必填');
        }
        if(!in_array($type,[1,2])){
            return msg_error('异常操作');
        }

        if(is_Admin()){
            return msg_error('暂时不支持管理员充值和提现');
        }

        $user = Auth();
        $data  = [
            'name' => $name,
            'type' => $type,
            'price' => $price,
            'card_type' => $card_type,
            'number' => $number,
            'remark' => $remark,
            'creator' => $user->name,
            'user_id' => $user->id,
            'status' => 0,
        ];
        if($type == 2){
            $money = sumMoney($user,true);
            if($money<$price){
                return msg_error('余额不足'.$price.'元');
            }
        }

        $order = (new OrderModel)->create($data);
        if($type == 2){
            $info = $this->createMoney(2,$order);
            if(!$info['status']){
                $order->remark = $order->remark.$info['msg'];
                $order->save();
            }
        }
        if($order){
            return msg_success('操作成功',$order->id);
        }else{
            return msg_error('操作失败');
        }
    }

    public function update(Request $request)
    {
        $id = $request->param('id');
        $del = $request->param('del');//是否无效操作
        $remark = $request->param('remark');//原因

        try {
            $order = (new OrderModel)->findOrFail($id);
        } catch (DataNotFoundException $e) {
            return msg_error('异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常',$e);
        }

        if(!$del){
            //确认操作
            $data['status'] = 1;
            if($order->type == 2){
                //提现
                $this->createOrder($id,2,2);
            }else{
                //充值
                $this->createOrder($id,1,1);
            }
        }else{
            //无效操作
            $data['status'] = 2;
            $data['admin_remark'] = $remark;
            if($order->type == 2){
                //提现
                $this->delOrder($id,2);
            }else{
                //充值
                $this->delOrder($id,1);
            }
        }

        $info = $order->save($data);
        if($info){
            return msg_success();
        }else{
            return msg_error('操作失败',$info);
        }
    }
    public function del(Request $request)
    {
        $id = $request->param('id');
        try {
            $order = (new OrderModel)->find($id);
            $order->delete();
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
