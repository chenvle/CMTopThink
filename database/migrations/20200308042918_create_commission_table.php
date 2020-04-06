<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateCommissionTable extends Migrator
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
        $table  =  $this->table('commission');
        $table->addColumn(Column::decimal('start',10,2)->setComment('范围开始'))
            ->addColumn(Column::decimal('end',10,2)->setComment('范围结束'))
            ->addColumn(Column::decimal('commission',10,2)->setComment('佣金价格'))
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
