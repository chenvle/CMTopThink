<?php
namespace app\admin\controller;

use app\BaseController;
use app\middleware\Auth;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Index extends BaseController
{
    /*
     * 中间件
     *
     * */
    protected $middleware = [Auth::class];

    public function index()
    {
        $notice_login = session('notice');
        session('notice',false);
        $notice = false;
        if($notice_login){
            try {
                $notice = (new \app\common\model\Notice)->order('id','desc')->select()->filter(function ($item){
                    if(time() >= strtotime($item->start_time) && time() <= strtotime($item->end_time)){
                        return $item;
                    }else{
                        return false;
                    }
                });
                if(count($notice)){
                    $notice = $notice[0];
                }else{
                    $notice = false;
                }
            } catch (DataNotFoundException $e) {
                dump($e);
            } catch (ModelNotFoundException $e) {
                dump($e);
            } catch (DbException $e) {
                dump($e);
            }
        }
        $menus = Menus();
        $data = sumMoney(Auth())['data'];
        return view('index',['menus' =>$menus, 'notice' =>$notice, 'money' => $data]);
    }

    public function welcome()
    {
        return view();
    }
}
