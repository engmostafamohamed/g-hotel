<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantReservation extends Model
{
    protected $fillable = [
        'restaurant_id',
        'guest_id',
        'order_type',
        'reservation_time',
        'notes',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function restaurantOrders()
    {
        return $this->hasMany(RestaurantOrder::class, 'reservation_id');
    }
}
