<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Reward extends Model
{
    //
    protected $fillable = ['name', 'type', 'value', 'description'];
    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    use SoftDeletes;
    public function points()
    {
        return $this->belongsToMany(Point::class, 'point_rewards')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'reward_services')
                    ->withTimestamps();
    }

    public function calculateDiscount($orderAmount)
    {
        if ($this->type === 'fixed') {
            return min($this->value, $orderAmount);
        }elseif ($this->type === 'percentage') {
            return ($orderAmount * $this->value) / 100;
        }
        return 0;
    }

    public function getTranslatedName(): string
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? $this->name['en'];
    }

    public function getTranslatedDescription(): ?string
    {
        $locale = app()->getLocale();
        return $this->description[$locale] ?? $this->description['en'] ?? null;
    }
}
