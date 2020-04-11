<?php


namespace app\admin\controller;

use app\BaseController;
use app\middleware\Auth;
use app\common\model\Line as LineModel;
use app\common\model\Task as TaskModel;
use app\common\model\Template as TemplateModel;
use app\Request;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Task extends BaseController
{
    /*
     * 中间件
     *
     * */
    protected $middleware = [];
    private $user_id;

    public function __construct(App $app)
    {
        $token         = session('token');
        $this->user_id = getToken($token);
        parent::__construct($app);
    }

    public function index()
    {
        return view('index');
    }

    public function making()
    {
        return view('making');
    }

    public function finish()
    {
        return view('finish');
    }

    public function error()
    {
        return view('error');
    }

    public function cancel()
    {
        return view('cancel');
    }

    public function check()
    {
        return view('check');
    }

    public function show(Request $request)
    {
        $id = $request->param('id');
        try {
            $task = (new TaskModel)->with(['line', 'store'])->findOrFail($id);
            if (!$task) {
                return msg_error();
            }
        } catch (DataNotFoundException $e) {
            return $e;
        } catch (ModelNotFoundException $e) {
            return $e;
        }
        $task_data = [];
        foreach ($task['task_date'] as $key => $item) {
            $task_data[] = [
                'task_date'   => $item,
                'task_number' => $task['task_number'][$key],
            ];
        }
        return view('show', ['task' => $task, 'task_data' => $task_data]);
    }

    public function edit(Request $request)
    {
        $id = $request->param('id');
        try {
            $task = (new TemplateModel)->with(['line', 'store'])->find($id);
            if (!$task) {
                return msg_error();
            }
        } catch (DataNotFoundException $e) {
            return $e;
        } catch (ModelNotFoundException $e) {
            return $e;
        } catch (DbException $e) {
            return $e;
        }
        $task_data = [];
        foreach ($task['task_date'] as $key => $item) {
            $task_data[] = [
                'task_date'   => $item,
                'task_number' => $task['task_number'][$key],
            ];
        }
        $templates = Auth()->templates;
        $stores    = Auth()->stores;
        $lines     = (new LineModel)->select();
        return view('create', ['task' => $task, 'task_data' => $task_data, 'templates' => $templates, 'lines' => $lines, 'stores' => $stores, 'is_create' => false]);
    }

    public function create()
    {
        $templates = Auth()->templates;
        $stores    = Auth()->stores;
        try {
            $lines = (new LineModel)->select();
            return view('create', ['templates' => $templates, 'lines' => $lines, 'stores' => $stores, 'is_create' => true]);
        } catch (DataNotFoundException $e) {
            dump($e);
        } catch (ModelNotFoundException $e) {
            dump($e);
        } catch (DbException $e) {
            dump($e);
        }
        return '异常';
    }
}