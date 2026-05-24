<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
class HotelLocation extends Model
{
    use HasTranslations;
    use SoftDeletes;

    protected $fillable = [
        'property_code',
        'display_name',
        'default_language',
        'default_currency',
        'timezone',
        'is_active',
        'lat',
        'long',
        'hotel_video_url',
        'location_name',
        'address',
    ];


    public $translatable = ['address'];

    public function getLocalizedLocationNameAttribute(): string
    {
        return $this->getTranslation('location_name', app()->getLocale());
    }
    public function services()
    {
        return $this->hasMany(Service::class, 'hotel_id');
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'hotel_id');
    }
    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id');
    }
    public function categories()
    {
        return $this->hasMany(Category::class, 'hotel_id');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class, 'hotel_id');
    }
    public function menuImports()
    {
        return $this->hasMany(MenuImport::class, 'hotel_id');
    }
    public function blackout_dates()
    {
        return $this->belongsToMany(BlackoutDate::class, 'blackout_date_categories');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'hotel_id');
    }

    public function contactInfos()
    {
        return $this->hasMany(ContactInfo::class);
    }

    public function liveStyleImages()
    {
        return $this->hasMany(LiveStyleImage::class, 'hotel_id');
    }

}
