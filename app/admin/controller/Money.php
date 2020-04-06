<?php

namespace app\admin\controller;

use app\BaseController;
use app\middleware\Auth;
use app\common\model\Money as MoneyModel;
use app\common\model\Order as OrderModel;
use app\Request;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Money extends BaseController
{
    /*
     * 中间件
     *
     * */
    protected $middleware = [Auth::class];
    protected $field = [
        'title'                   => null,//费用名称
        'model_type'              => null,//费用类型
        'model_name'              => null,//关联模块名称
        'frozen_amount'           => 0,//冻结金额
        'commission'              => 0,//佣金
        'other_cost'              => 0,//增值业务
        'return_amount'           => 0,//排单退还
        'return_commission'       => 0,//佣金退还
        'return_other_cost'       => 0,//返还增值业务
        'admin_return_amount'     => 0,//排单取消返还
        'admin_return_commission' => 0,//排单取消返回佣金
        'admin_return_other_cost' => 0,//排单取消返还增值业务
        'recharge'                => 0,//充值
        'withdraw'                => 0,//提现
        'return_withdraw'         => 0,//提现返还
        'user_id'                 => null,
        'task_id'                 => null,
        'order_id'                => null
    ];

    /*
     * $type = [
     *          1=>充值,
     *          2=>提现,
     *          3=>提现返还,
     *          4=>发布任务,
     *          5=>用户取消任务,
     *          6=>管理员取消任务,
     * ]
     *
     * */
    public function createMoney($type, $order = null, $task = null)
    {
        if (in_array($type, [1, 2, 3])) {
            $create_check = $this->orderModel($type, $order);
        } elseif (in_array($type, [4, 5, 6])) {
            $create_check = $this->taskModel($type, $task);
        } else {
            return msg_error('异常操作');
        }
        if ($create_check['status']) {
            $info = MoneyModel::create($this->field);
            if ($info) {
                return msg_success();
            } else {
                return msg_error();
            }
        } else {
            return msg_error();
        }
    }

    /**
     * 充值/提现产生金额变化
     * @param int $type
     * @param $order
     * @return array
     */
    protected function orderModel(int $type, $order)
    {
        $money = sumMoney($order->user);
        if (!$money['status']) {
            return $money;
        }
        try {
            $this->field['model_name'] = 'order';
            $this->field['user_id']    = $order->user_id;
            $this->field['order_id']   = $order->id;
            if ($type == 1) {
                $this->field['title']      = '充值' . now();
                $this->field['recharge']   = $order->price;
                $this->field['model_type'] = 1;//1=>增加费用 2=>减少费用
                return msg_success();
            } else if ($type == 2) {
                if ($money['data']['money'] < $order->price) {
                    return msg_error('余额不足');
                }
                $this->field['title']      = '提现' . now();
                $this->field['withdraw']   = $order->price;
                $this->field['model_type'] = 2;//1=>增加费用 2=>减少费用
                return msg_success();
            } else if ($type == 3) {
                $this->field['title']           = '提现返还' . now();
                $this->field['return_withdraw'] = $order->price;
                $this->field['model_type']      = 1;//1=>增加费用 2=>减少费用
                return msg_success();
            } else {
                return msg_error('操作异常');
            }
        } catch (Exception $e) {
            return msg_error('操作异常');
        }
    }

    /**
     * 任务产生金额变化
     * @param int $type
     * @param $task
     * @return array
     */
    protected function taskModel(int $type, $task)
    {

        /*
        'frozen_amount' => 0,//冻结金额
        'commission' => 0,//佣金
        'other_cost' => 0,//增值业务
        'return_amount' => 0,//排单退还
        'return_commission' => 0,//佣金退还
        'return_other_cost' => 0,//返还增值业务
        'admin_return_amount' => 0,//排单取消返还
        'admin_return_commission' => 0,//排单取消返回佣金
        'admin_return_other_cost' => 0,//排单取消返还增值业务
         * */
        $money = sumMoney($task->user);
        if (!$money['status']) {
            return $money;
        }

        try {
            $this->field['model_name'] = 'task';
            $this->field['user_id']    = $task->user_id;
            $this->field['task_id']    = $task->id;
            if ($type == 4) {//发布任务
                if ($money['data']['money'] < $task->frozen_amount) {
                    return msg_error('余额不足');
                }
                $this->field['title']         = '发布任务' . now();
                $this->field['model_type']    = 2;//1=>增加费用 2=>减少费用
                $this->field['frozen_amount'] = $task->frozen_amount;//冻结金额
                $this->field['commission']    = $task->commission;//佣金
                $this->field['other_cost']    = other_cost($task);//增值业务
                return msg_success();
            } else if ($type == 5) {//用户取消
                $this->field['title']             = '用户取消任务' . now();
                $this->field['model_type']        = 1;//1=>增加费用 2=>减少费用
                $this->field['return_amount']     = $task->frozen_amount;//排单退还
                $this->field['return_commission'] = $task->commission;//佣金退还
                $this->field['return_other_cost'] = other_cost($task);//返还增值业务
                return msg_success();
            } else if ($type == 6) {//管理员取消
                $this->field['title']                   = '管理员取消任务' . now();
                $this->field['model_type']              = 1;//1=>增加费用 2=>减少费用
                $this->field['admin_return_amount']     = $task->frozen_amount;//排单取消返还
                $this->field['admin_return_commission'] = $task->commission;//排单取消返回佣金
                $this->field['admin_return_other_cost'] = other_cost($task);//排单取消返还增值业务
                return msg_success();
            } else {
                return msg_error('操作异常');
            }
        } catch (Exception $e) {
            return msg_error('操作异常');
        }
    }

    /**
     * 无效充值订单
     * @param $order_id
     * @param $model_type
     * @return array
     */
    protected function delOrder($order_id, $model_type)
    {
        $where = [
            'order_id'   => $order_id,
            'model_type' => $model_type,
            'model_name' => 'order',
        ];
        try {
            $order_money = (new MoneyModel)->where($where)->select();
            if (count($order_money) < 1) {
                return msg_success('没有产生费用');
            } else {
                $order_money[0]->delete();
                return msg_success();
            }
        } catch (DataNotFoundException $e) {
            return msg_error();
        } catch (ModelNotFoundException $e) {
            return msg_error();
        } catch (DbException $e) {
            return msg_error();
        }
    }

    /**
     * 确认充值订单
     * @param $order_id
     * @param $model_type
     * @param $money_type
     * @return array
     */
    protected function createOrder($order_id, $model_type, $money_type)
    {
        $where = [
            'order_id'   => $order_id,
            'model_type' => $model_type,
            'model_name' => 'order',
        ];
        try {
            $order_money = (new MoneyModel)->withTrashed()->where($where)->select();
            $order       = (new OrderModel)->find($order_id);

            if (count($order_money) < 1) {
                /**
                 * 创建订单
                 */
                $this->createMoney($money_type, $order, null);
                return msg_success();
            } else {
                $order_money[0]->restore();
                return msg_success();
            }
        } catch (DataNotFoundException $e) {
            return msg_error();
        } catch (ModelNotFoundException $e) {
            return msg_error();
        } catch (DbException $e) {
            return msg_error();
        }
    }

    public function sumMoneyAllStatus()
    {
        try {
            $moneys = (new MoneyModel)->select()->toArray();
            $sum_1 = array_sum(array_column($moneys,'frozen_amount'));
            $sum_2 = array_sum(array_column($moneys,'commission'));
            $sum_3 = array_sum(array_column($moneys,'other_cost'));
            $sum_4 = array_sum(array_column($moneys,'return_amount'));
            $sum_5 = array_sum(array_column($moneys,'return_commission'));
            $sum_6 = array_sum(array_column($moneys,'return_other_cost'));
            $sum_7 = array_sum(array_column($moneys,'admin_return_amount'));
            $sum_8 = array_sum(array_column($moneys,'admin_return_commission'));
            $sum_9 = array_sum(array_column($moneys,'admin_return_other_cost'));
            $sum_10 = array_sum(array_column($moneys,'recharge'));
            $sum_11 = array_sum(array_column($moneys,'withdraw'));
            $sum_12 = array_sum(array_column($moneys,'return_withdraw'));
            $data=[
                'sum_1'=>$sum_1,
                'sum_2'=>$sum_2,
                'sum_3'=>$sum_3,
                'sum_4'=>$sum_4,
                'sum_5'=>$sum_5,
                'sum_6'=>$sum_6,
                'sum_7'=>$sum_7,
                'sum_8'=>$sum_8,
                'sum_9'=>$sum_9,
                'sum_10'=>$sum_10,
                'sum_11'=>$sum_11,
                'sum_12'=>$sum_12,
            ];
            return msg_success('ok',$data);
        } catch (DataNotFoundException $e) {
            return msg_error('操作异常',$e);
        } catch (ModelNotFoundException $e) {
            return msg_error('操作异常',$e);
        } catch (DbException $e) {
            return msg_error('操作异常',$e);
        }
    }

}
