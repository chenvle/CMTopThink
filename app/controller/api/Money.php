<?php
namespace app\controller\api;

use app\BaseController;
use app\middleware\Auth;
use app\Request;
use think\App;

use app\model\Money as MoneyModel;
use think\db\exception\DbException;

class Money extends BaseController
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

    public function getMoneyData(Request $request)
    {
        try {
            $limit = $request->param('limit');
            $key = $request->param('key');
            $type = $request->param('type');
            $order = (new MoneyModel);
            if($key){
                $order = $order->where('title','like','%'.$key.'%');
            }
            if(!is_Admin()){
                $order = $order->where('user_id',Auth()->id);
            }
            if($type){
                $order = $order->where('model_type',$type);
            }
            $info = $order->order('id','desc')->paginate($limit);
            return msg_success('ok',$info);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }
    }
    public function now()
    {
        $data = sumMoney(Auth())['data'];
        return msg_success('刷新成功',$data);
    }
}
