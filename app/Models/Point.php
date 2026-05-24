<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    //
    protected $fillable = ['points'];
    public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'point_rewards')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
