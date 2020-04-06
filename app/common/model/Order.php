<?php


namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class Order extends Model
{
    use SoftDelete;
    public function money()
    {
        return $this->hasOne(Money::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}