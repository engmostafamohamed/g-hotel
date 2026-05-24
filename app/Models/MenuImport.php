<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuImport extends Model
{
    protected $fillable = [
        'import_id',
        'restaurant_id',
        'hotel_location_id',
        'menu_type',
        'csv_file_path',
        'new_items',
        'updated_items',
        'errors',
        'report_url',
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function hotelLocation()
    {
        return $this->belongsTo(HotelLocation::class);
    }
}
