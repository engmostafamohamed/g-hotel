<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class LoyaltyTier extends Model
{
    protected $table='loyalty_tiers';
    use SoftDeletes;
    protected $fillable=[
        'code',
        'tier_name',
        'threshold',
        'content'
    ];
    protected $casts = [
        'tier_name' => 'array',
        'content' => 'array',
    ];

    public $translatable = ['tier_name', 'content'];

    public function LoyaltyAccounts()
    {
        return $this->hasMany(LoyaltyAccount::class, 'tier_id');
    }
}
