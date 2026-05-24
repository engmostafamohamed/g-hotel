<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SeasonalPrice extends Model
{
    protected $table = 'seasonal_prices';
    protected $fillable = [
        'room_type_id',
        'from',
        'to',
        'price',
        'points_discount'
    ];

    protected $casts = [
        'from' => 'date',
        'to' => 'date',
        // 'price' => 'decimal:2',
        // 'points_discount' => 'decimal:2'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
