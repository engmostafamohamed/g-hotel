<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ContactInfo extends Model
{
    use HasTranslations;
    protected $fillable = [
        'hotel_location_id',
        'type',
        'label',
        'value',
    ];

    public $translatable=['label'];

    protected $casts = [
        'label' => 'array',
    ];

    public function getLocalizedLabelAttribute(): string
    {
        return $this->getTranslation('label', app()->getLocale());
    }

    public function hotelLocation()
    {
        return $this->belongsTo(HotelLocation::class);
    }
}
