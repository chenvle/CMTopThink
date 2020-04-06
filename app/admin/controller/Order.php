<?php


namespace app\admin\controller;


use app\BaseController;
use app\middleware\Auth;
use app\common\model\Order as OrderModel;
use app\Request;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\View;

class Order extends BaseController
{
    /*
         * 中间件
         *
         * */
    protected $middleware = [Auth::class];

    /**
     *后端充值
     * @return View
     */
    public function apply_log()
    {
        return view();
    }
    /**
     *后端提现
     * @return View
     */
    public function withdraw_log()
    {
        return view();
    }

    /**
     * 后端充值提现历史记录
     * @return View
     */
    public function log()
    {
        return view();
    }

    public function create(Request $request)
    {
        $type = $request->param('type');
        $sets = setting();
        return view('create', ['type' => $type, 'sets' => $sets]);
    }

    public function edit(Request $request)
    {
        try {
            $order_id = $request->param('id');
            $order    = (new OrderModel)->findOrEmpty($order_id);
        } catch (DataNotFoundException $e) {
            return dump($e);
        } catch (ModelNotFoundException $e) {
            return dump($e);
        } catch (DbException $e) {
            return dump($e);
        }
        return view('edit', ['info' => $order]);
    }
    public function myMoney()
    {
        return view();
    }
}