<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreatePermissionTable extends Migrator
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
        $table  =  $this->table('permission');
        $table->addColumn('name', 'string',array('limit'  =>  64,'comment'=>'名称'))
            ->addColumn('display_name', 'string',array('limit'  =>  32,'comment'=>'显示名称'))
            ->addColumn('type', 'string',array('limit'  =>  32,'comment'=>'权限类型'))
            ->addTimestamps()
            ->addSoftDelete()
            ->create();
    }
}
