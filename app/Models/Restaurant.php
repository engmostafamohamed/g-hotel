<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasTranslations;
    protected $table = 'restaurants';
    protected $fillable = ['name', 'cuisine', 'image_url', 'hotel_id','in_dining','room_service'];

    public $translatable = ['name', 'cuisine'];

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }

    public function exceptions()
    {
        return $this->morphMany(Exception::class, 'schedulable');
    }
    public function getLocalizedNameAttribute(): string
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    public function getLocalizedCuisineAttribute(): string
    {
        return $this->getTranslation('cuisine', app()->getLocale());
    }
    public function hotelLocation()
    {
        return $this->belongsTo(HotelLocation::class, 'hotel_id');
    }
    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }
    public function menuImports()
    {
        return $this->hasMany(MenuImport::class);
    }
    public function getImageUrlAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        return url('uploads/restaurantImages/' . $value);
    }
}
