<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateRoleTable extends Migrator
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
    public function up()
    {
        $table  =  $this->table('role');
        $table->addColumn('name', 'string',array('limit'  =>  64,'comment'=>'名称'))
            ->addColumn('type', 'string',array('limit'  =>  32,'comment'=>'类型'))
            ->addTimestamps()
            ->addSoftDelete()
//            ->addIndex(array('type'), array('unique'  =>  true))
            ->create();
    }
}
