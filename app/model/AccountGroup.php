<?php


namespace app\model;


use think\Model;

class AccountGroup extends Model
{
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}