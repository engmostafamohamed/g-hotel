<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasTranslations;
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'images',
        'description',
        'hotel_id',
        'max_adults',
        'max_children',
        'infants_allowed',
        'policies',
        // 'blackout_id'
    ];

    protected $casts = [
        'name' => 'array',
        'images' => 'array',
        'description' => 'array',
        'policies' => 'array',
        'infants_allowed' => 'boolean',
    ];

    public $translatable = ['name', 'description', 'policies'];

    public function getLocalizedNameAttribute(): string
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        return $this->getTranslation('description', app()->getLocale());
    }

    public function getLocalizedPoliciesAttribute(): array
    {
        $locale = app()->getLocale();
        $policies = $this->getTranslation('policies', $locale);

        return is_array($policies) ? array_values($policies) : [];
    }

    public function getImagesAttribute($value): array
    {
        $images = is_array($value) ? $value : json_decode($value, true) ?? [];

        return collect($images)->map(function ($image) {
            return url('uploads/categoryImages/' . $image);
        })->toArray();
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function beds()
    {
        return $this->belongsToMany(Bed::class, 'category_bed')->withPivot('quantity')->withTimestamps();
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_categories');
    }

    public function hotelLocation()
    {
        return $this->belongsTo(HotelLocation::class, 'hotel_id');
    }

    public function blackout_dates()
    {
        return $this->belongsToMany(BlackoutDate::class, 'blackout_date_categories');
    }
}
