<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTask extends Migrator
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
        $table  =  $this->table('task');
        $table->addColumn(Column::integer('line_id')->setComment('线路'))
            ->addColumn(Column::integer('store_id')->setComment('店铺'))
            ->addColumn(Column::string('tid')->setComment('任务订单号'))
            ->addColumn(Column::string('shop_order_id')->setNull(true)->setComment('商城交易号'))
            ->addColumn(Column::string('img')->setComment('商品截图'))
            ->addColumn(Column::decimal('unit_price',10,2)->setComment('商品价格'))
            ->addColumn(Column::decimal('freight',10,2)->setComment('运费'))
            ->addColumn(Column::integer('type')->setComment('快递类型'))
            ->addColumn(Column::decimal('commission',10,2)->setComment('佣金价格'))
            ->addColumn(Column::decimal('exchange_rate',10,2)->setComment('当前汇率'))
            ->addColumn(Column::decimal('frozen_amount',10,2)->setComment('冻结金额'))
            ->addColumn(Column::string('amount')->setComment('数量'))
            ->addColumn(Column::integer('periods')->setComment('任务期数'))
            ->addColumn(Column::string('task_number')->setComment('发布任务数'))
            ->addColumn(Column::string('task_date')->setComment('发布时间'))
            ->addColumn(Column::string('Receipt_date')->setComment('收货时间'))
            ->addColumn(Column::string('keyword')->setNull(true)->setComment('关键词搜索'))
            ->addColumn(Column::string('search_key')->setNull(true)->setComment('搜索提醒'))
            ->addColumn(Column::string('remark')->setNull(true)->setComment('订单留言'))
            ->addColumn(Column::boolean('DSR')->setNull(true)->setComment('动态评分'))
            ->addColumn(Column::string('good_comment')->setNull(true)->setComment('好评评语'))
            ->addColumn(Column::boolean('sellers_how')->setNull(true)->setComment('卖家秀'))
            ->addColumn(Column::boolean('collection_shops')->setNull(true)->setComment('收藏店铺'))
            ->addColumn(Column::boolean('collection_products')->setNull(true)->setComment('收藏产品'))
            ->addColumn(Column::string('admin_remark')->setNull(true)->setComment('订单备注'))
            ->addColumn(Column::string('cancel_type')->setNull(true)->setComment('取消类型 user=>用户取消  admin=>管理员取消'))
            ->addColumn(Column::integer('user_id')->setComment('用户'))
            ->addColumn(Column::integer('account_id')->setComment('刷单'))
            ->addColumn(Column::integer('status')->setDefault(0)->setComment('状态'))
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
