<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class LiveStyleImage extends Model
{
    use HasTranslations;
    protected $table = 'live_style_images';
    protected $fillable = ['caption','images_url' ,'hotel_id'];

    public $translatable = ['caption'];
        protected $casts = [
        'images_url' => 'array', 
    ];

    public function getLocalizedCaptionAttribute(): string
    {
        return $this->getTranslation('caption', app()->getLocale());
    }
    public function hotelLocation()
    {
        return $this->belongsTo(HotelLocation::class, 'hotel_id');
    }
}
