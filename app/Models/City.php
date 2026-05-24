<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'country_id'];
    public $translatable = ['name'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
