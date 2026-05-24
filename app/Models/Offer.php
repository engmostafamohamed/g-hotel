<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'type',
        'value',
        'total_inventory',
        'per_guest_inventory',
        'start_date',
        'end_date',
        'service_id',
        'redemption_code',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
