<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyTransaction extends Model
{
    //
    
    protected $fillable = [
        'balance_after',
        'type',
        'points_change',
        'source',
        'source_id',
        'account_id',
        'valid_from',
        'expires_at',
    ];
    public function account()
    {
        return $this->belongsTo(LoyaltyAccount::class, 'account_id');
    }
}
