<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class RoomType extends Model
{
    use HasTranslations;

    protected $table = 'room_types';

    protected $fillable = [
        'room_code',
        'name',
        'description',
        'base_price',
        'category_id',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        // 'base_price' => 'decimal:2',
    ];

    public $translatable = ['name', 'description'];

    public function getLocalizedNameAttribute(): string
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        return $this->getTranslation('description', app()->getLocale());
    }

    public function getActiveSeasonalPriceAttribute()
    {
        $today = now()->toDateString();

        return $this->seasonalPrices()
            ->whereDate('from', '<=', $today)
            ->whereDate('to', '>=', $today)
            ->orderBy('from', 'desc')
            ->first();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seasonalPrices()
    {
        return $this->hasMany(SeasonalPrice::class);
    }

    public function views()
    {
        return $this->belongsToMany(View::class, 'room_type_view')->withTimestamps();
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function bookingRooms()
    {
        return $this->hasManyThrough(Booking::class, Room::class, 'room_type_id', 'room_id');
    }
}
