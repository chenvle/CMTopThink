<?php


namespace app\api\controller;

use app\ApiBaseController;
use app\common\model\User as UserModel;
use app\Request;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use \think\facade\Request as RequestFacade;

class Auth extends ApiBaseController
{
    /*
     * 中间件
     *
     * */
    protected $middleware = [];
    public function login(Request $request)
    {
        if ($this->request->isPost()) {
            if($this->api_key !== RequestFacade::header('ApplicationID')){
                return msg_error_api('秘钥没有授权');
            }
            $username = $this->request->param('username');
            $password = $this->request->param('password');
            if (!trim($username) || !$password) {
                return msg_error_api('请填写正确的账号和密码！');
            }
            try {
                $c_user     = UserModel::where('username', trim($username))->findOrFail();
                $c_password = makePassword($password);
                if ($c_password !== $c_user->password) {
                    return msg_error_api('密码错误');
                } else {
                    if ($c_user->frozen == 1) {
                        return msg_error_api('账户异常');
                    } else {
                        $token = makeToken($c_user->id);
                        return msg_success_api('登录成功',  $c_user,$token);
                    }
                }
            } catch (DataNotFoundException $e) {
                return msg_error_api('数据不存在', $e);
            } catch (ModelNotFoundException $e) {
                return msg_error_api('用户不存在', $e);
            } catch (DbException $e) {
                return msg_error_api('异常', $e);
            }
        } else {
            return msg_error_api('Please use POST type!');
        }
    }

    public function user()
    {
        /**
         * Api认证
         */
        $api_auth = api_auth($this->token);
        if (!$api_auth['status']) {
            return json($api_auth);
        }
        return msg_success_api('ok',$api_auth['data']);

    }
}