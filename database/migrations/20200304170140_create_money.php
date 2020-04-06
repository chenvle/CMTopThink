<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateMoney extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table  =  $this->table('money');
        $table->addColumn('title', 'string',array('comment'=>'费用名称'))
            ->addColumn('model_type','integer',array('comment'=>'费用类型 1=>增加费用 2=>减少费用 '))
            ->addColumn('model_name','string',array('comment'=>'关联模块名称 task=>发布任务产生的费用变动，order=>充值和提现产生的费用'))
            ->addColumn(Column::decimal('frozen_amount',10,2)->setDefault(0)->setComment('冻结金额'))
            ->addColumn(Column::decimal('commission',10,2)->setDefault(0)->setComment('佣金'))
            ->addColumn(Column::decimal('other_cost',10,2)->setDefault(0)->setComment('增值业务'))
            ->addColumn(Column::decimal('return_amount',10,2)->setDefault(0)->setComment('排单退还'))
            ->addColumn(Column::decimal('return_commission',10,2)->setDefault(0)->setComment('佣金退还'))
            ->addColumn(Column::decimal('return_other_cost',10,2)->setDefault(0)->setComment('返还增值业务'))
            ->addColumn(Column::decimal('admin_return_amount',10,2)->setDefault(0)->setComment('排单取消返还'))
            ->addColumn(Column::decimal('admin_return_commission',10,2)->setDefault(0)->setComment('排单取消返回佣金'))
            ->addColumn(Column::decimal('admin_return_other_cost',10,2)->setDefault(0)->setComment('排单取消返还增值业务'))
            ->addColumn(Column::decimal('recharge',10,2)->setDefault(0)->setComment('充值'))
            ->addColumn(Column::decimal('withdraw',10,2)->setDefault(0)->setComment('提现'))
            ->addColumn(Column::decimal('return_withdraw',10,2)->setDefault(0)->setComment('提现返还'))
            ->addColumn(Column::integer('user_id')->setNull(true)->setComment('用户'))
            ->addColumn(Column::integer('task_id')->setNull(true)->setComment('任务'))
            ->addColumn(Column::integer('order_id')->setNull(true)->setComment('充值/提现'))
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
