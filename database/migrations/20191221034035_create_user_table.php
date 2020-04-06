<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateUserTable extends Migrator
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
        $table  =  $this->table('user');
        $table->addColumn('username', 'string',array('limit'  =>  15,'default'=>'','comment'=>'用户名，登陆使用'))
            ->addColumn('password', 'string',array('limit'  =>  32,'comment'=>'用户密码'))
            ->addColumn('name', 'string',array('limit'  =>  64,'default'=>'','comment'=>'昵称'))
            ->addColumn('frozen', 'boolean',array('limit'  =>  1,'default'=>0,'comment'=>'登陆状态'))
            ->addColumn('token', 'string',array('limit'  =>  64,'null'=>true,'comment'=>'用户token'))
            ->addColumn('Avatar', 'string',array('limit'  =>  255,'null'=>true,'comment'=>'头像'))
            ->addColumn('Email', 'string',array('limit'  =>  255,'null'=>true,'comment'=>'email'))
            ->addColumn('Description', 'string',array('limit'  =>  255,'null'=>true,'comment'=>'个人介绍'))
            ->addColumn('Occupation', 'string',array('limit'  =>  255,'null'=>true,'comment'=>'职业'))
            ->addColumn('HomePage', 'string',array('limit'  =>  255,'null'=>true,'comment'=>'个人主页'))
            ->addColumn('Phone', 'integer',array('limit'  =>  11,'null'=>true,'comment'=>'手机号码'))
            ->addColumn('Sex', 'integer',array('limit'  =>  1,'default'=>1,'comment'=>'性别：1=》男 2=》女'))
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex(array('username'), array('unique'  =>  true))
            ->create();
    }
}
