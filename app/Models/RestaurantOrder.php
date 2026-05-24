<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantOrder extends Model
{
    protected $fillable = [
        'reservation_id',
        'menu_item_id',
        'quantity',
    ];

    public function reservation()
    {
        return $this->belongsTo(RestaurantReservation::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
