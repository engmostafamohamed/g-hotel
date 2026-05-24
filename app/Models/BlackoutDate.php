<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class BlackoutDate extends Model
{
    use HasTranslations;
    protected $table= 'blackout_dates';
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'allow_existing_booking',
        'hotel_id',
    ];
    public $translatable = ['name'];
    public function Categories(){
        return $this->belongsToMany(Category::class , 'blackout_date_categories');
    }
    public function hotelLocation()
    {
        return $this->belongsTo(HotelLocation::class, 'hotel_id');
    }
}
