<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateOrder extends Migrator
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
        $table  =  $this->table('order');
        $table->addColumn(Column::decimal('price',10,2)->setComment('金额'))
            ->addColumn('type', 'integer',array('comment'=>'类型 1=>充值 2=》提现'))
            ->addColumn('card_type','integer',array('comment'=>'账户类型 1=》支付宝 2=》银行账号'))
            ->addColumn('number','string',array('comment'=>'账号（支付宝、银行）'))
            ->addColumn('name','string',array('comment'=>'真实姓名'))
            ->addColumn('remark','string',array('comment'=>'备注','null'=>true,'limit'=>244))
            ->addColumn('creator','string',array('comment'=>'申请人','limit'=>244))
            ->addColumn('user_id','integer',array('comment'=>'用户ID','limit'=>244))
            ->addColumn('status','integer',array('comment'=>'状态（0=》待操作 1=》成功 2=》无效）','limit'=>2))
            ->addTimestamps()
            ->addSoftDelete()
            ->create();

    }
}
