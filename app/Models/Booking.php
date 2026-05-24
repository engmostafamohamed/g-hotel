<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = [
        'hotel_id', 'guest_id', 'room_id',
        'booking_date', 'booking_time',
        'arrival_date', 'arrival_time',
        'departure_date', 'departure_time',
        'num_adults', 'num_children',
        'special_reg', 'loyalty_points_earned', 
        'loyalty_points_redeemed', 'checked_out', 'total_price',
        'created_by', 'updated_by',
        // 'status', 'cancellation_reason'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    //remove after editing the booking logic in the app
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'booking_room', 'booking_id', 'room_id');
    }

    public function hotel()
    {
        return $this->belongsTo(HotelLocation::class, 'hotel_id', 'id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'booking_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }
}
