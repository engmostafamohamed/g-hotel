<?php
namespace App\Http\Requests\V1\CRM\Survey;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'question_title' => 'sometimes|required|array',
            'question_title.en' => 'required_with:question_title|string|max:255',
            'question_title.ar' => 'required_with:question_title|string|max:255',
            'question_type' => 'sometimes|in:text,multiple_choice,rating',

            'min_rating' => 'sometimes|required_if:question_type,rating|integer|min:1',
            'max_rating' => 'sometimes|required_if:question_type,rating|integer|gte:min_rating',

            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'string|max:100',
            // 'employee_id' => 'sometimes|exists:employees,id',
            'surveyable_id' => 'sometimes|integer',
            'surveyable_type' => 'sometimes|string|max:100',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'question_title.string' => __('survey.title_must_be_string'),
            'question_title.max' => __('survey.title_max_255_characters'),

            'question_type.in' => __('survey.invalid_question_type'),

            'options.required_if' => __('survey.options_required_for_multiple_choice'),
            'options.array' => __('survey.options_must_be_array'),
            'options.*.string' => __('survey.each_option_must_be_string'),
            'options.*.max' => __('survey.each_option_max_100_characters'),

            'min_rating.required_if' => __('survey.min_rating_required'),
            'max_rating.required_if' => __('survey.max_rating_required'),
            'max_rating.gte' => __('survey.max_rating_gte_min'),
            // 'employee_id.exists' => __('invalid_employee_id'),
            'surveyable_id.integer' => __('survey.surveyable_id_must_be_integer'),

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
