<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exception extends Model
{
    protected $fillable = [
        'schedulable_type',
        'schedulable_id',
        'date',
        'exception_from',
        'exception_to',
    ];

    public function schedulable()
    {
        return $this->morphTo();
    }
}
