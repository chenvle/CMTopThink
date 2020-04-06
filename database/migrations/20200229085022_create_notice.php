<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateNotice extends Migrator
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
        $table  =  $this->table('notice');
        $table->addColumn('title', 'string',array('limit'  =>  120,'comment'=>'公告标题'))
            ->addColumn('content', 'text',array('null'=>true,'comment'=>'公告内容'))
            ->addColumn('start_time','date',array('comment'=>'公告显示时间'))
            ->addColumn('end_time','date',array('comment'=>'公告结束时间'))
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
