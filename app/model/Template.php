<?php


namespace app\model;


use think\Model;

class Template extends Model
{
    protected $type = [
        'task_date'   => 'array',
        'task_number' => 'array',
        'Receipt_date' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}