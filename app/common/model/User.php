<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;


class User extends Model
{
    use SoftDelete;

    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function templates()
    {
        return $this->hasMany(Template::class);
    }
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
    public function moneys()
    {
        return $this->hasMany(Money::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
