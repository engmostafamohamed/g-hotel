<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'channels',
        'created_by',
        'approved_by',
        'approval_required',
        'is_approved',
        'estimated_reach',
        'status',
    ];

    protected $casts = [
        'channels' => 'array',
        'approval_required' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function offer()
    {
        return $this->hasOne(CampaignOffer::class);
    }
    public function preview()
    {
        return $this->hasOne(CampaignPreview::class);
    }
}
