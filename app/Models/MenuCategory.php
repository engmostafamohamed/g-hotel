<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MenuCategory extends Model
{
    use HasTranslations;
    protected $fillable = ['restaurant_id', 'name'];

    public $translatable = ['name'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'menu_category_id');
    }
}
