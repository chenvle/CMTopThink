<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateSet extends Migrator
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
        $table  =  $this->table('set');
        $table->addColumn('name', 'string',array('comment'=>'名称(en)'))
            ->addColumn('value','string',array('comment'=>'值'))
            ->addColumn('display_name','string',array('comment'=>'名称'))
            ->create();
    }
}
