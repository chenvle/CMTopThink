<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Role extends Model
{
    use SoftDelete;

    public function users()
    {
        return $this->belongsToMany(User::class,'role_user');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'role_permission');
    }
}
