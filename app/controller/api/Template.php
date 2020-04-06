<?php
namespace app\controller\api;

use app\BaseController;
use app\middleware\Auth;
use app\model\Template as TemplateModel;
use app\Request;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Template extends BaseController
{
    /*
    * 中间件
    *
    * */
    protected $middleware = [Auth::class];

    private $user_id;

    public function __construct(App $app)
    {
        $token = session('token');
        $this->user_id = getToken($token);
        parent::__construct($app);
    }

    public function getTemplateData(Request $request){
        try {
            $limit = $request->param('limit');
            $service = (new TemplateModel);
            $info = $service->where('user_id',$this->user_id)->order('id','desc')->paginate($limit);
            return msg_success('ok',$info);
        } catch (DbException $e) {
            return msg_error('异常',$e);
        }
    }

//
//    public function store(Request $request)
//    {
//        $data = $request->param('data');
//        if(isset($data['sellers_how'])){
//            $data['sellers_how'] = true;
//        }
//        if(isset($data['collection_shops'])){
//            $data['collection_shops'] = true;
//        }
//        if(isset($data['collection_products'])){
//            $data['collection_products'] = true;
//        }
//        $data['user_id'] = $this->user_id;
//        $data['tid'] = create_order();
//        $data['Receipt_date'] = date('Y-m-d H:i:s',strtotime("+15 days",strtotime($data['task_date'])));
//        unset($data['file']);
//        $service_id = (new TemplateModel)->insertGetId($data);
//        if($service_id){
//            return msg_success('操作成功',$service_id);
//        }else{
//            return msg_error('操作失败');
//        }
//    }
    public function find(Request $request)
    {
        $id = $request->param('id');
        try {
            $template = (new TemplateModel)->findOrFail($id);
            return msg_success('ok', $template);
        } catch (DataNotFoundException $e) {
            return msg_error();
        } catch (ModelNotFoundException $e) {
            return msg_error();
        }
    }
    public function del(Request $request)
    {
        $id = $request->param('id');
        try {
            $role = (new TemplateModel)->find($id);
            $role->delete();
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
