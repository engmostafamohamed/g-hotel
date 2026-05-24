<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class StaticPage extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'last_updated',
        'is_active',
    ];

    public $translatable = ['title', 'content'];

    protected $casts = [
        'last_updated' => 'datetime',
        'is_active' => 'boolean',
    ];
}
