<?php
namespace App\Http\Resources\V1\CRM\Survey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class SurveyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_title' => [
                'en' => $this->question_title['en'] ?? null,
                'ar' => $this->question_title['ar'] ?? null,
            ],
            'question_type' => $this->question_type,
            'options' => $this->options,
            'max_rating' => $this->max_rating ?? null,
            'min_rating' => $this->min_rating ?? null,
            'employee_id' => $this->employee_id,
            'surveyable_id' => $this->surveyable_id,
            'surveyable_type' => $this->surveyable_type,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
