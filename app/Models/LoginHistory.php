<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $fillable = [
        'employee_id',
        'login_time',
        'logout_time',
        'ip_address',
        'user_agent',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    protected $casts = [
        'login_timet' => 'datetime',
        'logout_time' => 'datetime',
    ];
}
