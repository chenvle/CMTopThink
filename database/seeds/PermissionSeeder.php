<?php

use think\migration\Seeder;

class PermissionSeeder extends Seeder
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
        $permission_lang_cn = config('permission_lang_cn');
        $route = config('menus');
        $admin_permissions = $this->get_permission($route);
        $ins = [];
        foreach ($admin_permissions as $admin_permission)
        {
            $ins[]=[
                'name'=>$admin_permission,
                'display_name'=>isset($permission_lang_cn[$admin_permission])?$permission_lang_cn[$admin_permission]:$admin_permission,
                'type'=>'admin'
            ];
        }

        $this->table('permission')->insert($ins)->save();

        $route = config('userMenus');
        $user_permissions = $this->get_permission($route);
        $ins = [];
        foreach ($user_permissions as $user_permission)
        {
            $ins[]=[
                'name'=>$user_permission,
                'display_name'=>isset($permission_lang_cn[$user_permission])?$permission_lang_cn[$user_permission]:$user_permission,
                'type'=>'user'
            ];
        }

        $this->table('permission')->insert($ins)->save();


        $permissions = config('permissions');
        $ins = [];
        foreach ($permissions as $permission)
        {
            $ins[]=[
                'name'=>$permission['name'],
                'display_name'=>$permission['display_name'],
                'type'=>$permission['type']
            ];
        }
        $this->table('permission')->insert($ins)->save();


    }

    protected function get_permission($arr,$permissions = [])
    {
        foreach ($arr as $item)
        {
            if(isset($item['route']) && !in_array($item['route'],$permissions)){
                $permissions[]=$item['route'];
            }
            if(isset($item['children']) && is_array($item['children'])){
                $temp = $this->get_permission($item['children'],$permissions);
                $permissions = array_keys(array_flip($permissions)+array_flip($temp));
            }
        }
        return $permissions;
    }
}