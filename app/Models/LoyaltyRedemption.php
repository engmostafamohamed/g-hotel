<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyRedemption extends Model
{
    protected $fillable = [
        'account_id',
        'reward_id',
        'idempotency_key',
        'status',
        'fulfilled_at',
    ];
    public function account()
    {
        return $this->belongsTo(LoyaltyAccount::class, 'account_id');
    }
    public function reward()
    {
        return $this->belongsTo(LoyaltyReward::class, 'reward_id');
    }
}
