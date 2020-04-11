<?php

namespace app\admin\controller\api;

use app\BaseController;
use app\middleware\Auth;
use app\common\model\Notice as NoticeModel;
use app\Request;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Notice extends BaseController
{
    /*
    * 中间件
    *
    * */
    protected $middleware = [];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function getNoticeData(Request $request)
    {
        try {
            $limit   = $request->param('limit');
            $key     = $request->param('key');
            $service = (new NoticeModel);
            if ($key) {
                $service = $service->where('title', 'like', '%' . $key . '%');
            }
            $info = $service->order('id', 'desc')->paginate($limit);
            return msg_success('ok', $info);
        } catch (DbException $e) {
            return msg_error('异常', $e);
        }
    }

    public function store(Request $request)
    {
        $title      = $request->param('title');
        $start_time = $request->param('start_time');
        $end_time   = $request->param('end_time');
        $content    = $request->param('content');

        if (!$title || strlen($title) < 4 || strlen($title) > 25) {
            return msg_error('标题必填,且长度大于4并小于25');
        }

        $data       = [
            'title'      => $title,
            'start_time' => $start_time,
            'end_time'   => $end_time,
            'content'    => $content,
        ];
        $service_id = (new NoticeModel)->insertGetId($data);
        if ($service_id) {
            return msg_success('操作成功', $service_id);
        } else {
            return msg_error('操作失败');
        }
    }

    public function update(Request $request)
    {
        $title      = $request->param('title');
        $start_time = $request->param('start_time');
        $end_time   = $request->param('end_time');
        $content    = $request->param('content');
        $id         = $request->param('id');


        try {
            if (!$title || strlen($title) < 4 || strlen($title) > 25) {
                return msg_error('标题必填,且长度大于4并小于25');
            }
            $service = (new NoticeModel)->findOrFail($id);
        } catch (DataNotFoundException $e) {
            return msg_error('异常', $e);
        } catch (ModelNotFoundException $e) {
            return msg_error('异常', $e);
        }


        $data = [
            'title'      => $title,
            'start_time' => $start_time,
            'end_time'   => $end_time,
            'content'    => $content,
        ];

        $info = $service->save($data);
        if ($info) {
            return msg_success('操作成功', $info);
        } else {
            return msg_error('操作失败', $info);
        }
    }

    public function del(Request $request)
    {
        $id = $request->param('id');
        try {
            $role = (new NoticeModel)->find($id);
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
