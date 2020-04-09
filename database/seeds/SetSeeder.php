<?php

use think\migration\Seeder;

class SetSeeder extends Seeder
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
                'name'=>'account',
                'value'=>'11223344556',
                'display_name'=>'充值账号'
            ],[
                'name'=>'qr_img',
                'value'=>'',
                'display_name'=>'充值二维码'
            ],[
                'name'=>'account_two',
                'value'=>'22334455667',
                'display_name'=>'充值账号'
            ],[
                'name'=>'qr_img_two',
                'value'=>'',
                'display_name'=>'充值二维码'
            ],[
                'name'=>'rate',
                'value'=>'6.87',
                'display_name'=>'汇率'
            ],
        ];
        $this->table('set')->insert($admin_data)->save();
    }
}