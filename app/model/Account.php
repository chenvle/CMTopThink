<?php


namespace app\model;


use think\Model;

class Account extends Model
{
    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class);
    }
}