<?php

use think\migration\Seeder;

class OtherSeeder extends Seeder
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
        $admin_data = [
            [
                'start'=>'0',
                'end'=>'10',
                'commission'=>'1'
            ],[
                'start'=>'10',
                'end'=>'20',
                'commission'=>'2'
            ],[
                'start'=>'20',
                'end'=>'99999',
                'commission'=>'3'
            ],
        ];
        $this->table('commission')->insert($admin_data)->save();

        $id = 2;

        $admin_data = [
            [
                'name'=>'店铺一',
                'user_id'=>$id,
            ],[
                'name'=>'店铺二',
                'user_id'=>$id,
            ],[
                'name'=>'店铺三',
                'user_id'=>$id,
            ],[
                'name'=>'店铺4',
                'user_id'=>$id,
            ],
        ];
        $this->table('store')->insert($admin_data)->save();
        $admin_data = [
            [
                'name'=>'线路一'
            ],[
                'name'=>'线路二'
            ],[
                'name'=>'线路三'
            ],[
                'name'=>'线路4'
            ],
        ];
        $this->table('line')->insert($admin_data)->save();

    }
}