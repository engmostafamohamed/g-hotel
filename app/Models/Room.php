<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'room_number',
        'room_type_id',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
    
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_room', 'booking_id', 'room_id');
    }
}
