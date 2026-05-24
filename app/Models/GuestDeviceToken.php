<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestDeviceToken extends Model
{
    protected $fillable = ['guest_id', 'device_token', 'platform'];


    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
