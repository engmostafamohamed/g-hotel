<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'surveys';
    protected $casts = [
        'question_title' => 'array',
        'options' => 'array',
    ];
    protected $fillable = [
        'question_title',
        'question_type',
        'options',
        'max_rating',
        'min_rating',
        'employee_id',
        'surveyable_id',
        'surveyable_type',
        'is_active',
    ];
    public function surveyable()
    {
        return $this->morphTo();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'survey_answers')
                    ->withPivot(['text_answer', 'selected_option', 'rating_answer'])
                    ->withTimestamps();
    }

}
