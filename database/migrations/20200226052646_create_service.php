<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateService extends Migrator
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
        $table  =  $this->table('service');
        $table->addColumn('name', 'string',array('limit'  =>  15,'comment'=>'客服名称'))
            ->addColumn('qq', 'string',array('limit'  =>  32,'null'=>true,'comment'=>'QQ'))
            ->addColumn('qr', 'string',array('limit'  =>  254,'null'=>true,'comment'=>'微信二维码'))
            ->addColumn('phone', 'string',array('limit'  =>  20,'null'=>true,'comment'=>'登陆状态'))
            ->addColumn('display', 'boolean',array('limit'  =>  2,'default'=>1,'comment'=>'是否启用'))
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
