<?php


namespace app\controller;


use app\BaseController;
use app\middleware\Auth;
use app\model\Notice as NoticeModel;
use app\Request;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Notice extends BaseController
{
    /*
         * 中间件
         *
         * */
    protected $middleware = [Auth::class];

    public function index()
    {
        return view();
    }
    public function create()
    {
        return view();
    }
    public function edit(Request $request)
    {
        try {
            $service_id = $request->param('id');
            $service = (new NoticeModel)->findOrEmpty($service_id);
        } catch (DataNotFoundException $e) {
            return dump($e);
        } catch (ModelNotFoundException $e) {
            return dump($e);
        } catch (DbException $e) {
            return dump($e);
        }
        return view('edit',['info'=>$service]);
    }
}