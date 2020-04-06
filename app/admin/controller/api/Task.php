<?php

namespace app\admin\controller\api;

use app\BaseController;
use app\admin\controller\Money;
use app\middleware\Auth;
use app\common\model\Task as TaskModel;
use app\common\model\Template as TemplateModel;
use app\Request;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Task extends Money
{
    /**
     * 中间件
     * @var array
     */
    protected $middleware = [Auth::class];

    private $user_id;

    public function __construct(App $app)
    {
        $token         = session('token');
        $this->user_id = getToken($token);
        parent::__construct($app);
    }

    public function getTaskData(Request $request)
    {
        try {
            $limit  = $request->param('limit');
            $status = $request->param('status');
            $where  = [];
            if ($status != '') {
                $where['status'] = is_numeric($status) ? $status : task_status($status);
            }
            if (!is_Admin()) {
                $where['user_id'] = $this->user_id;
            }elseif($status==3){
                unset($where['status']);
                $status = [3,4];
            }
            $tasks = (new TaskModel)->with(['line', 'store']);
            $tasks = $tasks->where($where);
            if(is_Admin() && is_array($status)){
                $tasks = $tasks->whereIn('status',$status);
            }
            $tasks = $tasks->order('id', 'desc')->paginate($limit);
            return msg_success('ok', $tasks);
        } catch (DbException $e) {
            return msg_error();
        }
    }

    /**
     * 发布任务
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $data = $request->param();
        if (isset($data['sellers_how'])) {
            $data['sellers_how'] = true;
        }
        if (isset($data['collection_shops'])) {
            $data['collection_shops'] = true;
        }
        if (isset($data['collection_products'])) {
            $data['collection_products'] = true;
        }
        $data['user_id'] = $this->user_id;
        $account         = get_account();
        if (!$account['status']) {
            return $account;
        }
        $data['account_id']   = $account['msg'];
        $data['tid']          = create_order();
        $data['Receipt_date'] = date('Y-m-d H:i:s', strtotime("+15 days", time()));
        unset($data['file']);


        if (isset($data['title']) && $data['title']) {
            if(isset($data['id'])){
                $task = (new TemplateModel)->update($data,['id'=>$data['id']]);
            }else{
                $task = (new TemplateModel)->create($data);
            }

        } else {

            $money = sumMoney($this->user_id, true);
            if ($money < $data['frozen_amount']) {
                return msg_error('余额不足，请及时充值' . $money);
            }
            $task = (new TaskModel)->create($data);
            $info = $this->createMoney(4, null, $task);
            if (!$info['status']) {
                (new TaskModel)->where($task)->delete();
            }
        }
        if ($task) {
            return msg_success('操作成功');
        } else {
            return msg_error('操作失败');
        }
    }

    /**
     * 取消任务/异常任务
     * @param Request $request
     * @return array
     */
    public function cancel(Request $request)
    {
        $id     = $request->param('id');

        try {
            $task = (new TaskModel)->find($id);
            if (is_Admin()) {
                $remark = $request->param('remark');
                $task->cancel_type = 'admin';
                $task->admin_remark = $remark;
                $task->status       = task_status('异常');
                $info =  $this->createMoney(5,null,$task);
            } else {
                $task->cancel_type = 'user';
                $task->status       = task_status('已取消');
                $info =  $this->createMoney(6,null,$task);
            }
            if($info['status']){
                $task->save();
            }else{
                return $info;
            }
            return msg_success();
        } catch (DataNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (DbException $e) {
            return msg_error('异常', $e);
        }
    }
    /**
     * 接受任务/完成任务
     * @param Request $request
     * @return array
     */
    public function taskPass(Request $request)
    {
        $id     = $request->param('id');
        $status     = $request->param('status');
        $shop_order_id     = $request->param('shop_order_id');
        try {
            $task = (new TaskModel)->find($id);
            $task->status       = task_status($status);
            $task->shop_order_id       = $shop_order_id;
            $task->save();
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
