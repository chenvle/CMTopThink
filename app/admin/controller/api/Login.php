<?php

namespace app\admin\controller\api;

use app\BaseController;
use app\common\model\User as UserModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Login extends BaseController
{
    public function auto()
    {
        if ($this->request->isPost()) {
            $username = $this->request->param('username');
            $password = $this->request->param('password');
            if (!trim($username) || !$password) {
                return msg_error('请填写正确的账号和密码！');
            }
            try {
                $c_user     = UserModel::where('username', trim($username))->findOrFail();
                $c_password = makePassword($password);
                if ($c_password !== $c_user->password) {
                    return msg_error('密码错误');
                } else {
                    if ($c_user->frozen == 1) {
                        return msg_error('账户异常');
                    } else {
//                        if(!is_Admin($c_user)){
//                            return msg_error('非管理员禁止登录后台');
//                        }
                        $token = makeToken($c_user->id);
                        session('token', $token);
                        session('notice', true);
                        return msg_success('登录成功');
                    }
                }
            } catch (DataNotFoundException $e) {
                return msg_error('数据不存在', $e);
            } catch (ModelNotFoundException $e) {
                return msg_error('用户不存在', $e);
            } catch (DbException $e) {
                return msg_error('异常', $e);
            }
        } else {
            return msg_error('Please use POST type!');
        }
    }

    public function logout()
    {
        session('token', null);
        return msg_success('退出成功');
    }

}
