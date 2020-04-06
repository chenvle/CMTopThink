<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

class Permission extends Model
{
    use SoftDelete;

    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_permission');
    }
}
