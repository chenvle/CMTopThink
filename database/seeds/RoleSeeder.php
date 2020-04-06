<?php

use think\migration\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $admin = [
            [
                'name'=>'admin',
                'type'=>'admin',
            ],
            [
                'name'=>'user',
                'type'=>'user',
            ]
        ];
        $this->table('role')->insert($admin)->save();
    }
}