<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Country extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'country_code','iso_code'];
    public $translatable = ['name'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
