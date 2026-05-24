<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignPreview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'email_html',
        'push_message',
    ];

    protected $casts = [
        'email_html' => 'string',
        'push_message' => 'string',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
