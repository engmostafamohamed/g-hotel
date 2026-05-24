<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasTranslations;

    protected $table = 'views';

    protected $fillable = [
        'type'
    ];

    protected $casts = [
        'type' => 'array',
    ];

    public $translatable=[
        'type'
    ];
    public function getLocalizedTypeAttribute(): string
    {
        return $this->getTranslation('type', app()->getLocale());
    }
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_view')->withTimestamps();
    }
}
