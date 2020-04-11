<?php
declare (strict_types=1);

namespace app\middleware;

use Closure;
use think\db\exception\DbException;
use think\Request;
use think\Response;

/**
 * Class Auth
 * @package app\middleware
 */
class Auth
{
    /**
     * @var
     */
    protected $user;
    /**
     * @var array
     * 不通过中间件的路由,无需登陆
     */
    protected $no_auth_controller = [
        'login/index',
        'index/index',
        'index/welcome',
    ];
    /**
     * @var array
     * 不通过中间件的路由,但需要登陆
     */
    protected $no_auth_controller_with_login = [

    ];

    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $route = $request->pathinfo();
        $App   = App('http')->getName();
        if ($App == 'admin') {
            if (in_array(strtolower($route), $this->no_auth_controller)) {
                return $next($request);
            }
            $msg = $this->auth();
            if (substr($route, 0, 3) != 'api') {
                if (!$msg['status']) {
                    session('Message', $msg['msg']);
                    if ($msg['data'] && $msg['data'] == 'admin') {
                        return redirect('/admin/login/index?type=admin');
                    } else {
                        return redirect('/admin/login/index');
                    }
                } else {
                    if (!permission($route) && !in_array('admin', array_column(Auth()->roles->toArray(), 'name'))) {
                        session('Message', '没有权限');
                        return redirect('/admin/power');
                    }

                    return $next($request);
                }
            } else {
                return $next($request);
            }
        } else {
            return $next($request);
        }

    }

    /**
     * @return array
     */
    public function auth()
    {
        try {
            $token = session('token');
            if (!$token) {
                return msg_error('请登录', false);
            }
            $user_id = getToken($token);
            if (!is_numeric($user_id)) {
                return msg_error('异常', false);
            }
            $this->user = \app\common\model\User::find($user_id);
            if ($this->user->frozen == 1) {
                return msg_error('账户异常', false);
            }
            if (is_Admin($this->user)) {
                return msg_success('管理员', 'admin');
            }
            return msg_success('会员', 'user');
        } catch (DbException $e) {
            return msg_error('异常');
        }

    }
}
