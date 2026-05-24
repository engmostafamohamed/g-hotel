<?php
namespace App\Http\Repository\V1\CRM\Survey;
use App\Models\Survey;
use App\DataTransferObjects\V1\CRM\Survey\StoreSurveyDTO;

class SurveyRepository
{
    public function all($request)
    {
        try {
            return Survey::latest()->paginate($request->input('per_page', 10));
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function find($id): ?Survey
    {
        try {
            return Survey::findOrFail($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function create(StoreSurveyDTO $data)
    {
        try {
            $survey = Survey::create($data->toArray());
            return $survey;
        } catch (\Exception $e) {
            return [
                'status' => 'survey_not_created'
            ];
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $survey = Survey::find($id);

            if (!$survey) {
                return [
                    'status' => 'survey_not_found',
                    'data' => null
                ];
            }

            $survey->fill(
                [
                    'question_title' => $data['question_title'] ?? $survey->question_title,
                    'question_type' => $data['question_type'] ?? $survey->question_type,
                    'is_active' => $data['is_active'] ?? $survey->is_active,
                ]
            );

            if(array_key_exists('surveyable_id', $data)){
                $survey->surveyable_id = $data['surveyable_id'];

            }

            if (array_key_exists('surveyable_type', $data)) {
                $survey->surveyable_type = $data['surveyable_type'];
            }

            if(isset($data['employee_id'])){
                $survey->employee_id = $data['employee_id'];
            }
            $questionType = $data['question_type'] ?? $survey->question_type;
            if ($questionType === 'rating') {
                $survey->min_rating = $data['min_rating'] ?? $survey->min_rating;
                $survey->max_rating = $data['max_rating'] ?? $survey->max_rating;
                $survey->options = null; // Clear options for rating type
            } elseif ($questionType === 'multiple_choice' ) {
                $survey->options = $data['options'] ?? $survey->options??[];
                $survey->min_rating = null; // Clear ratings for multiple choice type
                $survey->max_rating = null;
            } else { // text type
                $survey->min_rating = null;
                $survey->max_rating = null;
                $survey->options = null; // Clear options for text type
            }

            $survey->save();
            return [
                'status' => 'survey_updated',
                // 'data' => $survey->fresh()
            ];
        } catch (\Throwable $e) {

            // \Log::error('Survey update failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'survey_not_updated',
                'data' => null
            ];
        }
    }

    public function delete(string $id){
        try {
            $survey = Survey::find($id);
            if (!$survey){
                return [
                    'status' => 'survey_not_found'
                ];
            }
            $survey->delete();
            return [
                'status' => 'survey_deleted'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'survey_not_found'
            ];
        }
    }
}
