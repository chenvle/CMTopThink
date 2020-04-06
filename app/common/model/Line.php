<?php


namespace app\common\model;


use think\Model;

class Line extends Model
{
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}