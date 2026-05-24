<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $fillable = [
        'guest_id',
        'survey_id',
        'text_answer',
        'selected_option',
        'rating_answer',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
