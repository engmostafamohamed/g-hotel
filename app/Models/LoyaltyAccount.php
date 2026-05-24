<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyAccount extends Model
{
    protected $table ="loyalty_accounts";
    use SoftDeletes;
    protected $fillable=[
        'balance',
        'lifetime_earned',
        'lifetime_redeemed',
        'user_id',
        'tier_id',
    ];
    public function rewards(){
       return $this->belongsToMany(LoyaltyReward::class,'loyalty_redemptions');
    }
    public function transactions(){
        return $this->hasMany(LoyaltyTransaction::class,'account_id');
    }


}
