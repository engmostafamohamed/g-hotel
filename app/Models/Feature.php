<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasTranslations;
    protected $fillable = ['name','hotel_id','logo'];

    public $translatable=['name'];

    public function getLocalizedNameAttribute(): string
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    public function getLogoAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        return url('uploads/featureLogos/' . $value);
    }
    public function Categories(){
        return $this->belongsToMany(Category::class , 'feature_categories');
    }
}