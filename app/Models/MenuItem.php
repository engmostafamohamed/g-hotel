<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MenuItem extends Model
{
    use HasTranslations;
    protected $fillable = [
        'menu_category_id',
        'name',
        'description',
        'price',
        'dietary_tags',
    ];

    protected $casts = [
        'dietary_tags' => 'array',
    ];
    public $translatable = ['name', 'description'];

    public function getLocalizedNameAttribute(): string
    {
        return $this->getTranslation('name', app()->getLocale());
    }
    public function getLocalizedDescriptionAttribute(): string
    {
        return $this->getTranslation('description', app()->getLocale());
    }
    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
}
