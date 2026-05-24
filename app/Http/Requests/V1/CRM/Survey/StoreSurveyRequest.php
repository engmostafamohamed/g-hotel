<?php
namespace App\Http\Requests\V1\CRM\Survey;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'question_title' => 'required|array',
            'question_title.en' => 'required|string|max:255',
            'question_title.ar' => 'required|string|max:255',
            'question_type' => 'required|in:text,multiple_choice,rating',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'string|max:100',
            'min_rating' => 'required_if:question_type,rating|integer|min:1',
            'max_rating' => 'required_if:question_type,rating|integer|gte:min_rating',
            'employee_id' => 'required|exists:employees,id',
            'surveyable_id' => 'nullable|integer',
            'surveyable_type' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
        ];

    }
    public function messages(): array
    {
        return [
            'question_title.required' => __('survey.question_title_required'),
            'question_title.en.required' => __('survey.english_title_required'),
            'question_title.ar.required' => __('survey.arabic_title_required'),
            'question_title.en.string' => __('survey.title_must_be_string'),
            'question_title.en.max' => __('survey.title_max_255_characters'),
            'question_title.ar.string' => __('survey.title_must_be_string'),
            'question_title.ar.max' => __('survey.title_max_255_characters'),

            'question_type.required' => __('survey.question_type_is_required'),
            'question_type.in' => __('survey.invalid_question_type'),

            'options.required_if' => __('survey.options_required_for_multiple_choice'),
            'options.array' => __('survey.options_must_be_array'),
            'options.*.string' => __('survey.each_option_must_be_string'),
            'options.*.max' => __('survey.each_option_max_100_characters'),

            'min_rating.required_if' => __('survey.min_rating_required'),
            'max_rating.required_if' => __('survey.max_rating_required'),
            'max_rating.gte' => __('survey.max_rating_gte_min'),

            'employee_id.required' => __('survey.employee_id_is_required'),
            'employee_id.exists' => __('survey.invalid_employee_id'),

            'surveyable_id.required' => __('survey.surveyable_id_is_required'),
            'surveyable_id.integer' => __('survey.surveyable_id_must_be_integer'),

            'surveyable_type.required' => __('survey.surveyable_type_is_required'),
            'surveyable_type.string' => __('survey.surveyable_type_must_be_string'),
            'surveyable_type.max' => __('survey.surveyable_type_max_100_characters'),

            'is_active.boolean' => __('survey.is_active_must_be_boolean'),
        ];
    }
    protected function prepareForValidation(): void
    {
        // Automatically inject employee_id from the token (authenticated employee)
        $employee = auth('employee')->user();

        if ($employee) {
            $this->merge([
                'employee_id' => $employee->id,
            ]);
        }
    }
}
