<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Tier extends Model
{
    // use HasTranslations;
    protected $table = 'tiers';
    protected $fillable = [
        'tier_name',
        'min_nights',
        'tier_value',
        'content'
    ];
    protected $casts = [
        'tier_name' => 'array',
        'content' => 'array',

    ];

    public $translatable = ['tier_name', 'content'];
    public function getLocalizedNameAttribute(): string
    {
        return $this->getTranslation('tier_name', app()->getLocale());
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_tier');
    }
    public function guests()
    {
        return $this->hasMany(Guest::class, 'tier_id');
    }
}
