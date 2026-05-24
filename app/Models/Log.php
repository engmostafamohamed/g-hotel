<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    protected $fillable = [
        'employee_id',
        'action',
        'model_type',
        'model_id',
        'changes',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


}
