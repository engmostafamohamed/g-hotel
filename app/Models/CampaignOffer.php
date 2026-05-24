<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignOffer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'type',
        'value',
        'min_booking',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
