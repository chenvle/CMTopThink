<?php


namespace app\model;


use think\Model;
use think\model\concern\SoftDelete;

class Task extends Model
{
    use SoftDelete;
    protected $dateFormat = 'Y-m-d';

    public function getStatusAttr($value)
    {
        return task_status($value);
    }

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

    public function moneys()
    {
        return $this->hasMany(Money::class);
    }

}