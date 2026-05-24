<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class Service extends Model
{
    use HasTranslations;
    protected $table = 'services';
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image_path',
        'sync_with_pms',
        'pms_sync_status',
        'version',
        'hotel_id',
        'locations'
    ];

    public $translatable = ['name', 'description'];
    protected $casts = [
        'locations' => 'array',
    ];

    public function getLocalizedNameAttribute(): string
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        return $this->getTranslation('description', app()->getLocale());
    }
    public function hotelLocation()
    {
        return $this->belongsTo(HotelLocation::class, 'hotel_id');
    }
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(ServiceTimeSlot::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function tiers()
    {
        return $this->belongsToMany(Tier::class, 'service_tier');
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }

    public function exceptions()
    {
        return $this->morphMany(Exception::class, 'schedulable');
    }

    public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'reward_services')
                    ->withTimestamps();
    }

    public function isSchedulable(): bool
    {
        return $this->timeSlots()->exists();
    }

    public function serviceReservations()
    {
        return $this->hasMany(ServiceReservation::class, 'service_id');
    }

}
