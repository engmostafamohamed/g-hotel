<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasTranslations;

    protected $table = 'beds';

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
    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_bed')->withPivot('quantity')->withTimestamps();
    }
}
