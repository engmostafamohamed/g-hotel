<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class ContactNumber extends Model
{
    use HasTranslations;
    protected $table = 'contact_numbers';

    protected $fillable = ['label', 'number'];

    public $translatable = ['label'];

    public function getLocalizedTitleAttribute(): string
    {
        return $this->getTranslation('label', app()->getLocale());
    }
}
