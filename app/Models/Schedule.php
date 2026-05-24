<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'schedulable_id',
        'schedulable_type',
        'day_of_week',
        'work_from',
        'work_to',
    ];

    protected $casts = [
        'work_from' => 'datetime:H:i:s',
        'work_to' => 'datetime:H:i:s',
    ];

    public function schedulable()
    {
        return $this->morphTo();
    }
}
