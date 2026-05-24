<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'guest_id',
        'booking_id',
        'service_reservation_id',
        'rating',
        'comment',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function serviceReservation()
    {
        return $this->belongsTo(ServiceReservation::class, 'service_reservation_id');
    }
}
