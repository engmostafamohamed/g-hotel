<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyReward extends Model
{
    protected $table ="loyalty_rewards";
    use SoftDeletes;
    protected $fillable=[
        'sku',
        'name',
        'cost_points',
        'stock',
        'active',
        'meta'
    ];
    protected $casts =[
        'name'=>'array',
        'meta'=>'array'
    ];

    public function LoyaltyAccount (){
        return $this->belongsToMany(LoyaltyAccount::class,'loyalty_redemptions');
    }

}
