<?php

use think\migration\Seeder;

class UserSeeder extends Seeder
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
        /*填充默认账号*/
        $admin_data = [
          'username'=>'cmtime',
          'password'=>makePassword('cmtime'),
          'name'=>'晨明网络',
          'Avatar'=>'https://blog.chenvle.com/usr/themes/echo/img/00.png',
        ];
        $this->table('user')->insert([$admin_data])->save();
        $user_data = [
          'username'=>'chenvle',
          'password'=>makePassword('chenvle'),
          'name'=>'用户端',
          'Avatar'=>'https://blog.chenvle.com/usr/themes/echo/img/00.png',
        ];
        $this->table('user')->insert([$user_data])->save();

        $other_user = [];
        $num = $this->unique_rand(10000,99999,50);
        for ($i = 0; $i < count($num); $i++) {
            $other_user[] = [
                'username' => 'user'.$num[$i],
                'name' => 'user'.$num[$i],
                'email' => mt_rand(10000, 99999).'@qq.com',
                'password' => makePassword('123456'),
            ];
        }

        $this->table('user')->insert($other_user)->save();

        // 填充关联
        $this->seeder_data();
    }


    protected function unique_rand($min, $max, $num) {
        $count = 0;
        $return = array();
        while ($count < $num) {
            $return[] = mt_rand($min, $max);
            $return = array_flip(array_flip($return));
            $count = count($return);
        }
        shuffle($return);
        return $return;
    }
    protected function seeder_data()
    {
        try {
            $role = \app\model\Role::find(1);
            $role->users()->attach([1]);
            $permissions = \app\model\Permission::where('type','admin')->select()->toArray();
            $permission_ids = array_column($permissions,'id');
            $role->permissions()->attach($permission_ids);

            $permissions = \app\model\Permission::where('type','user')->select()->toArray();
            $permission_ids = array_column($permissions,'id');
            $user_role = \app\model\Role::find(2);
            $user_role->users()->attach([2]);
            $user_role->permissions()->attach($permission_ids);
        } catch (\think\db\exception\DataNotFoundException $e) {
        } catch (\think\db\exception\ModelNotFoundException $e) {
        } catch (\think\db\exception\DbException $e) {
        }
    }
}