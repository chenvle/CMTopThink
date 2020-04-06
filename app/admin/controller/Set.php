<?php


namespace app\admin\controller;


use app\BaseController;
use app\middleware\Auth;
use app\common\model\Set as SetModel;
use app\Request;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Set extends BaseController
{
        /*
         * 中间件
         *
         * */
    protected $middleware = [Auth::class];

    public function index()
    {
        $sets = [];
        try {
            $sets = (new SetModel)->select();
        } catch (DataNotFoundException $e) {
            dump($e);
        } catch (ModelNotFoundException $e) {
            dump($e);
        } catch (DbException $e) {
            dump($e);
        }
        return view('index',['sets'=>$sets]);
    }
}