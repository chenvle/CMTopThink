<?php


namespace app\model;


use think\Model;

class Store extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}