<?php
// 应用公共文件

use app\admin\model\Column;
use app\admin\model\User;
use app\extend\Tree;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Env;
use think\facade\Log;
use think\Model;
use think\response\Json;

if (!function_exists('curl_post')) {

    /**
     * 获取语言变量值
     * @param $url
     * @param $data
     * @return mixed
     */
    function curl_post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}

if (!function_exists('curl_get')) {

    /**
     * 获取语言变量值
     * @param $url
     * @return mixed
     */
    function curl_get($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'ERROR' . curl_error($curl);
        }
        curl_close($curl);
        return $data;
    }
}


if (!function_exists('makePassword')) {

    /**
     * 密码
     * @param $password
     * @return mixed
     */
    function makePassword($password)
    {
        return encrypt($password, Env::get('key'));
    }
}

if (!function_exists('makeToken')) {
    /**
     * token
     * @param $id
     * @return mixed
     */
    function makeToken($id)
    {
        return encrypt($id . Env::get('key') . $id);
    }
}
if (!function_exists('getToken')) {
    /**
     * decrypt_token
     * @param $token
     * @return mixed
     */
    function getToken($token)
    {
        $str = decrypt($token);
        $key = Env::get('key');
        return explode($key, $str)[0];
    }
}


if (!function_exists('encrypt')) {
    /**
     * 加密
     * @param $data
     * @param string $key
     * @return string
     */
    function encrypt($data, $key = '112233')
    {
        $char = '';
        $str  = '';
        $key  = md5($key);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }
}

if (!function_exists('decrypt')) {
    /**
     * 解密
     * @param $data
     * @param string $key
     * @return string
     */
    function decrypt($data, $key = '112233')
    {
        $char = '';
        $str  = '';
        $key  = md5($key);
        $x    = 0;
        $data = base64_decode($data);
        $len  = strlen($data);
        $l    = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }
}
if (!function_exists('msg_data')) {
    /**
     * @param $data
     * @return array
     */
    function msg_data($data)
    {
        $data = [
            'status' => true,
            'msg'    => 'ok',
            'data'   => $data,
        ];
        return $data;
    }
}
if (!function_exists('msg_success')) {
    /**
     * @param string $msg
     * @param array $data
     * @return array
     */
    function msg_success($msg = '操作成功', $data = [])
    {
        $data = [
            'status' => true,
            'msg'    => $msg,
            'data'   => $data,
        ];
        return $data;
    }
}
if (!function_exists('msg_error')) {
    /**
     * @param string $msg
     * @param array $data
     * @return array
     */
    function msg_error($msg = '操作失败', $data = [])
    {
        $data = [
            'status' => false,
            'msg'    => $msg,
            'data'   => $data,
        ];
        return $data;
    }
}
if (!function_exists('msg_success_api')) {
    /**
     * @param string $msg
     * @param null $token
     * @param array $data
     * @return Json
     */
    function msg_success_api($msg = '操作成功',$data = [],$token=null)
    {
        $data = [
            'status' => true,
            'msg'    => $msg,
            'data'   => $data,
            'token'=>$token
        ];
        return json($data);
    }
}
if (!function_exists('msg_error_api')) {
    /**
     * @param string $msg
     * @param array $data
     * @return Json
     */
    function msg_error_api($msg = '操作失败', $data = [])
    {
        $data = [
            'status' => false,
            'msg'    => $msg,
            'data'   => $data,
        ];
        return json($data);
    }
}
if (!function_exists('get_message')) {
    /**
     * @param string $name
     * @return mixed
     */
    function get_message($name = 'Message')
    {
        $Message = session($name);
        session($name, null);
        return $Message;
    }
}
if (!function_exists('set_message')) {
    /**
     * @param $Message
     * @return mixed
     */
    function set_message($Message)
    {
        session($Message);
        return true;
    }
}

if (!function_exists('get_menus')) {
    /**
     * @return mixed
     */
    function get_menus()
    {
        $menus = config('menus');
        if (is_AuthAdmin()) {
            return $menus;
        } else {
            $permissions = [];
            foreach (Auth()->roles as $role) {
                $ids  = array_column($permissions, 'id');
                $info = $role->permissions->toArray();
                foreach ($info as $item) {
                    if (!in_array($item['id'], $ids)) {
                        $permissions[] = $item;
                    }
                }
            }
            if (count($permissions)) {
                $auth_permission_names = array_column($permissions, 'name');
                foreach ($menus as $k1 => $menu) {
                    if (!in_array($menu['route'], $auth_permission_names)) {
                        unset($menus[$k1]);
                    } else if (isset($menu['children'])) {
                        foreach ($menu['children'] as $k2 => $menu2) {
                            if (!in_array($menu2['route'], $auth_permission_names)) {

                                unset($menus[$k1]['children'][$k2]);
                            } else if (isset($menu2['children'])) {
                                foreach ($menu2['children'] as $k3 => $menu3) {
                                    if (!in_array($menu3['route'], $auth_permission_names)) {
                                        unset($menus[$k1]['children'][$k2]['children'][$k3]);
                                    }
                                }
                            }
                        }
                    }
                }
                return $menus;
            } else {
                return [];
            }
        }
    }
}
if (!function_exists('is_AuthAdmin')) {
    /**
     * 判断是否超级管理员
     * @return bool
     */
    function is_AuthAdmin()
    {
        $roles = array_column(Auth()->roles->toArray(), 'name');
        if (in_array('admin', $roles)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('is_Admin')) {
    /**
     * 判断是否管理员
     * @param string $user
     * @return bool
     */
    function is_Admin($user = '')
    {
        if (!$user) {
            $user = Auth();
        }
        if (!isset($user->roles)) {
            return false;
        }
        $roles     = $user->roles->toArray();
        $role_type = array_column($roles, 'type');
        if (in_array('admin', $role_type)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('Auth')) {
    /**
     * 当前用户
     * @param bool $token
     * @return array|Model
     */
    function Auth($token = false)
    {
        if(!$token){
            $token = session('token');
            $id    = getToken($token);
        }else{
            $id = getToken($token);
        }

        try {
            $user = (new app\common\model\User)->find($id);
        } catch (DataNotFoundException $e) {
            return msg_error();
        } catch (ModelNotFoundException $e) {
            return msg_error();
        } catch (DbException $e) {
            return msg_error();
        }
        return $user;
    }
}

if (!function_exists('Menus')) {
    /**
     * 当前用户
     * @param bool $user
     * @return array|Model
     */
    function Menus($user = false)
    {
        if (is_Admin($user?$user:Auth())) {
            $menus = config('menus');
        } else {
            $menus = config('userMenus');
        }
        return $menus;
    }
}

if (!function_exists('permission')) {
    /**
     * 判断是否有权限/获取所有权限
     * @param null $name
     * @return array|bool
     */
    function permission($name = null)
    {
        $permissions = [];
        if (is_Admin(Auth())) {
            $type = 'admin';
        } else {
            $type = 'user';
        }
        $roles = Auth()->roles;
        foreach ($roles as $role) {
            if ($role->type == $type) {
                $ids  = array_column($permissions, 'id');
                $info = $role->permissions->toArray();
                foreach ($info as $item) {
                    if (!in_array($item['id'], $ids)) {
                        $permissions[] = $item;
                    }
                }
            }
        }

        if ($name && count($permissions)) {
            $permissions_name = array_column($permissions, 'name');
            $permissions_name = array_to_lower($permissions_name);

            if (in_array(strtolower($name), $permissions_name)) {
                return true;
            } else {
                return false;
            }
        }

        return $permissions;
    }
}
/**
 * 转换成小写
 */
if (!function_exists('array_to_lower')) {
    function array_to_lower($weChatArr)
    {
        foreach ($weChatArr as $key => $weChat) {
            $byteArr2D[] = str_split(trim($weChat));
            foreach ($byteArr2D[$key] as $byte) {
                $byteToLowerArr2D[$key][] = ord($byte) >= 65 && ord($byte) <= 90 ? chr(ord($byte) + 32) : $byte;
            }
        }
        return array_map('implode', $byteToLowerArr2D);
    }
}
if (!function_exists('echo_upload_api')) {
    /**
     * 上传接口返回
     * @param $src
     * @param int $code
     * @param string $msg
     * @return array
     */
    function echo_upload_api($src, $code = 0, $msg = '')
    {
        return [
            'code' => $code,
            'msg'  => $msg,
            'data' => [
                'src' => $src
            ]
        ];
    }
}
if (!function_exists('setting')) {
    /**
     * 设置
     * @return Exception|DataNotFoundException|array
     */
    function setting()
    {
        try {
            $sets = (new \app\common\model\Set)->select();
            $info = [];
            foreach ($sets as $set) {
                $info[$set->name] = $set->value;
            }
            return $info;
        } catch (DataNotFoundException $e) {
            return $e;
        } catch (ModelNotFoundException $e) {
            return $e;
        } catch (DbException $e) {
            return $e;
        }
    }
}
if (!function_exists('task_status')) {
    /**
     * 设置
     * @param $code
     * @return Exception|DataNotFoundException|array
     */
    function task_status($code)
    {
        $data = [
            0 => '已申请',//会员发起任务
            1 => '处理中',//管理员接受任务
            2 => '已完成',//管理员点击已完成
            3 => '异常',//管理员点击异常
            4 => '已取消',//会员点击取消
        ];
        if ($code && in_array($code, array_values($data))) {
            return array_flip($data)[$code];
        }
        return $data[$code];
    }
}
if (!function_exists('create_order')) {
    /**
     * 设置
     * @return string
     */
    function create_order()
    {
        return 'CM' . date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}
if (!function_exists('now')) {
    /**
     * 时间
     * @param $format
     * @return string
     */
    function now($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }
}

if (!function_exists('other_cost')) {
    /**
     * 设置
     * @param $task
     * @return string
     */
    function other_cost($task)
    {
        //sellers_how collection_shops collection_products
        $i = 0;
        $task->sellers_how ? $i++ : false;
        $task->collection_shops ? $i++ : false;
        $task->collection_products ? $i++ : false;
        return $i * 0.5;
    }
}
if (!function_exists('sumMoney')) {
    /**
     * 设置
     * @param int|array $userOrId
     * @param bool $get_sum
     * @return array|int|float
     */
    function sumMoney($userOrId, $get_sum = false)
    {
        /*
        'frozen_amount'           => 0,//冻结金额*****
        'commission'              => 0,//佣金
        'other_cost'              => 0,//增值业务
        'return_amount'           => 0,//排单退还*****
        'return_commission'       => 0,//佣金退还
        'return_other_cost'       => 0,//返还增值业务
        'admin_return_amount'     => 0,//排单取消返还*****
        'admin_return_commission' => 0,//排单取消返回佣金
        'admin_return_other_cost' => 0,//排单取消返还增值业务
        'recharge'                => 0,//充值
        'withdraw'                => 0,//提现
        'return_withdraw'         => 0,//提现返还
         * */
        if (is_numeric($userOrId)) {
            try {
                $user = (new app\common\model\User)->find($userOrId);
            } catch (DataNotFoundException $e) {
                return msg_error('异常', $e);
            } catch (ModelNotFoundException $e) {
                return msg_error('异常', $e);
            } catch (DbException $e) {
                return msg_error('异常', $e);
            }
        } else {
            $user = $userOrId;
        }

        $moreMoneys     = $user->moneys->filter(function ($item) {
            if ($item->model_type == 1) {
                return $item;
            } else {
                return false;
            }
        })->toArray();
        $lessMoneys     = $user->moneys->filter(function ($item) {
            if ($item->model_type == 2) {
                return $item;
            } else {
                return false;
            }
        })->toArray();
        $moreMoneys_sum = array_sum(array_column($moreMoneys, 'return_amount')) * 100 +
            array_sum(array_column($moreMoneys, 'admin_return_amount')) * 100 +
            array_sum(array_column($moreMoneys, 'return_withdraw')) * 100 +
            array_sum(array_column($moreMoneys, 'recharge')) * 100;
        $lessMoneys_sum = array_sum(array_column($lessMoneys, 'frozen_amount')) * 100 +
            array_sum(array_column($lessMoneys, 'withdraw')) * 100;
        $sum            = $moreMoneys_sum - $lessMoneys_sum;

        if ($get_sum) {
            return (int)$sum/100;
        }

        if ($sum < 0) {
            return msg_error('账户异常，禁止交易，请联系管理员');
        } else {
            return msg_success('ok', ['user' => $user, 'get_money' => (int)$moreMoneys_sum /100, 'less_money' => (int)$lessMoneys_sum /100, 'money' => (int)$sum /100]);
        }
    }

    if (!function_exists('get_account')) {
        /**
         * 随机刷单账号
         */
        function get_account()
        {
            try {
                $accounts    = (new \app\common\model\Account)->select()->toArray();
                $account_ids = array_column($accounts, 'id');
                if(count($account_ids) < 1){
                    return msg_error('目前没有刷单账号，请联系管理员');
                }
                $num         = rand(0, count($account_ids) - 1);
                return msg_success($account_ids[$num]);
            } catch (DataNotFoundException $e) {
                return msg_error('目前没有刷单账号，请联系管理员', $e);
            } catch (ModelNotFoundException $e) {
                return msg_error('目前没有刷单账号，请联系管理员', $e);
            } catch (DbException $e) {
                return msg_error('目前没有刷单账号，请联系管理员', $e);
            }
        }
    }
    if (!function_exists('info')) {
        /**
         * 写入日志
         * @param $msg
         */
        function info($msg)
        {
            Log::info($msg);
        }
    }
    /**
     * Api认证
     */
    if (!function_exists('api_auth')) {
        function api_auth($token)
        {
            $user_id = getToken($token);
            if (!is_numeric($user_id)) {
                return msg_error('异常', false);
            }
            try {
                $user = \app\common\model\User::find($user_id);
            } catch (DataNotFoundException $e) {
                return msg_error('账户异常', false);
            } catch (ModelNotFoundException $e) {
                return msg_error('账户异常', false);
            } catch (DbException $e) {
                return msg_error('账户异常', false);
            }
            if ($user->frozen == 1) {
                return msg_error('账户异常', false);
            }
            return msg_success('会员', 'user');
        }
    }
}






