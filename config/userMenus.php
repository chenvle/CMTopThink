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
            ],[
                'title'=>'现金流水',
                'route'=>'Order/myMoney',
            ]
        ]
    ],[
        'title'=>'任务管理',
        'route'=>'Task',
        'icon'=>'layui-icon-list',
        'children'=>[
            [
                'title'=>'所有任务',
                'route'=>'Task/index',
            ],[
                'title'=>'发布任务',
                'route'=>'Task/create',
            ],[
                'title'=>'正在处理任务',
                'route'=>'Task/making',
            ],[
                'title'=>'已完成任务',
                'route'=>'Task/finish',
            ],[
                'title'=>'异常任务',
                'route'=>'Task/error',
            ],[
                'title'=>'已取消任务',
                'route'=>'Task/cancel',
            ]
        ]
    ],[
        'title'=>'基础管理',
        'route'=>'Set',
        'icon'=>'layui-icon-set-fill',
        'children'=>[
            [
                'title'=>'店铺管理',
                'route'=>'Store/index',
            ],[
                'title'=>'模板管理',
                'route'=>'Template/index',
            ]
        ]
    ],[
        'title'=>'其他服务',
        'route'=>'Other',
        'icon'=>'layui-icon-face-smile-fine',
        'children'=>[
            [
                'title'=>'在线客服',
                'route'=>'Service/index',
            ]
        ]
    ]
];
