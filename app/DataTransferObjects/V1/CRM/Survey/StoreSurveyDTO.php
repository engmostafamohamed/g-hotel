<?php

namespace App\DataTransferObjects\V1\CRM\Survey;
use App\Http\Requests\V1\CRM\Survey\StoreSurveyRequest;

class StoreSurveyDTO
{
    public function __construct(
        public array $question_title,
        public string $question_type,
        public ?array $options = null,
        public ?int $min_rating = 0,
        public ?int $max_rating = 0,
        public int $employee_id,
        public ?int $surveyable_id = null,
        public ?string $surveyable_type = null,
        public ?bool $is_active = true,
    ) {}

    public static function fromRequest(StoreSurveyRequest $request): self
    {
        return new self(
            question_title: [
                'en' => $request->input('question_title.en'),
                'ar' => $request->input('question_title.ar'),
            ],
            question_type: $request->input('question_type'),
            options: $request->input('options'),
            employee_id: $request->input('employee_id'),
            surveyable_id: $request->input('surveyable_id'),
            min_rating: $request->input('min_rating'),
            max_rating: $request->input('max_rating'),
            surveyable_type: $request->input('surveyable_type'),
            is_active: $request->input('is_active', true)
        );
    }

    public function toArray(): array
    {
        return [
            'question_title' => $this->question_title,
            'question_type' => $this->question_type,
            'options' => $this->options,
            'employee_id' => $this->employee_id,
            'surveyable_id' => $this->surveyable_id,
            'min_rating' => $this->min_rating,
            'max_rating' => $this->max_rating,
            'surveyable_type' => $this->surveyable_type,
            'is_active' => $this->is_active,
        ];
    }
}
