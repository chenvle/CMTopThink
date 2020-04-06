<?php


namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class Money extends Model
{

    use SoftDelete;
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}