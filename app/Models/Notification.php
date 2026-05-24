<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        'guest_id',
        'title',
        'message',
        'data',
        'scheduled_at',
        'notifiable_id',
        'notifiable_type',
        'to_all_guest',
        'sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'scheduled_at' => 'datetime',
        'title'=> 'array',
        'message'=> 'array',
    ];
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
    public function notifiable()
    {
        return $this->morphTo();
    }
}
