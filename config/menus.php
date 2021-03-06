<?php
// +----------------------------------------------------------------------+
// | 菜单                                                                  |
// +----------------------------------------------------------------------+

return [
    [
        'title'=>'工作台',
        'route'=>'Index/welcome',
        'icon'=>'layui-icon-home',
        'show'=>true
    ],[
        'title'=>'任务管理',
        'route'=>'Task',
        'icon'=>'layui-icon-ok-circle',
        'children'=>[
            [
                'title'=>'待审核任务',
                'route'=>'Task/check',
            ],[
                'title'=>'正在处理任务',
                'route'=>'Task/making',
            ],[
                'title'=>'已完成任务',
                'route'=>'Task/finish',
            ],[
                'title'=>'异常任务',
                'route'=>'Task/error',
            ]
        ]
    ],[
        'title'=>'资金管理',
        'route'=>'Money',
        'icon'=>'layui-icon-rmb',
        'children'=>[
            [
                'title'=>'充值申请管理',
                'route'=>'Order/apply_log',
            ],[
                'title'=>'提现申请管理',
                'route'=>'Order/withdraw_log',
            ],[
                'title'=>'充值提现历史记录',
                'route'=>'Order/log',
            ]
        ]
    ],[
        'title'=>'用户管理',
        'route'=>'User',
        'icon'=>'layui-icon-user',
        'children'=>[
            [
                'title'=>'所有管理员',
                'route'=>'User/admin',
            ],[
                'title'=>'所有会员',
                'route'=>'User/index',
            ],[
                'title'=>'权限设置',
                'route'=>'Permission/index',
            ],[
                'title'=>'角色管理',
                'route'=>'Role/index',
            ]
        ]
    ],[
        'title'=>'其他信息',
        'route'=>'Other',
        'icon'=>'layui-icon-user',
        'children'=>[
            [
                'title'=>'在线客服',
                'route'=>'Service/index',
            ],[
                'title'=>'刷单账号',
                'route'=>'Account/index',
            ],[
                'title'=>'佣金设置',
                'route'=>'Commission/index',
            ],[
                'title'=>'服务线路',
                'route'=>'Line/index',
            ],[
                'title'=>'公告',
                'route'=>'Notice/index',
            ],[
                'title'=>'设置',
                'route'=>'Set/index',
            ]
        ]
    ],
];
