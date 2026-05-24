<?php
namespace App\DataTransferObjects\V1\CRM\Survey;
use App\Http\Requests\V1\CRM\Survey\UpdateSurveyRequest;
class UpdateSurveyDTO
{
    public function __construct(
        public ?array $question_title = null,
        public ?string $question_type = null,
        public ?array $options = null,
        public ?int $min_rating = null,
        public ?int $max_rating = null,
        public ?int $employee_id = null,
        public ?int $surveyable_id = null,
        public ?string $surveyable_type = null,
        public ?bool $is_active = null,
    ) {}

    public static function fromRequest(UpdateSurveyRequest $request): self
    {
        $employeeId = auth('employee')->user()?->id;
        return new self(
            question_title: $request->input('question_title'),
            question_type: $request->input('question_type'),
            options: $request->input('options'),
            employee_id: $employeeId,
            surveyable_id: $request->input('surveyable_id'),
            surveyable_type: $request->input('surveyable_type'),
            is_active: $request->input('is_active')
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
            'surveyable_type' => $this->surveyable_type,
            'is_active' => $this->is_active,
        ];
    }
}
