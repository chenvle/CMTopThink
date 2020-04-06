<?php
// +----------------------------------------------------------------------+
// | 权限                                                                  |
// +----------------------------------------------------------------------+

return [
    [
        'name'=>'Store/create',
        'display_name'=>'新增店铺',
        'type'=>'user',
    ],[
        'name'=>'Store/edit',
        'display_name'=>'编辑店铺',
        'type'=>'user',
    ],[
        'name'=>'Service/create',
        'display_name'=>'添加客服',
        'type'=>'admin',
    ],[
        'name'=>'Task/show',
        'display_name'=>'查看任务详情',
        'type'=>'user',
    ],[
        'name'=>'Task/show',
        'display_name'=>'查看任务详情',
        'type'=>'admin',
    ],[
        'name'=>'Order/create',
        'display_name'=>'充值/提现申请',
        'type'=>'user',
    ],[
        'name'=>'Order/create_draw',
        'display_name'=>'提现',
        'type'=>'user',
    ],[
        'name'=>'Money/sumMoneyAllStatus',
        'display_name'=>'现金流水计算',
        'type'=>'user',
    ],[
        'name'=>'Task/edit',
        'display_name'=>'编辑模板',
        'type'=>'user',
    ],[
        'name'=>'Role/power',
        'display_name'=>'设置权限',
        'type'=>'admin',
    ],
];
