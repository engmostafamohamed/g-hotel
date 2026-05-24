<?php
namespace App\Http\Controllers\V1\CRM\Survey;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Survey\StoreSurveyRequest;
use App\Http\Requests\V1\CRM\Survey\UpdateSurveyRequest;
use App\Http\Repository\V1\CRM\Survey\SurveyRepository;
use App\Http\Resources\V1\CRM\Survey\SurveyResource;
use App\Http\Resources\V1\CRM\Survey\PaginatedSurveyResource;
use Illuminate\Http\Request;
use App\DataTransferObjects\V1\CRM\Survey\StoreSurveyDTO;
use App\DataTransferObjects\V1\CRM\Survey\UpdateSurveyDTO;
use App\Helpers\ApiResponse;
use App\Models\Survey;

class SurveyController extends Controller
{
    protected $surveyRepository;

    public function __construct(SurveyRepository $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    public function index(Request $request)
    {
        $surveys = $this->surveyRepository->all($request);
        if ($surveys->isEmpty()) {
            return ApiResponse::error(__('survey.surveys_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('survey.data_fetched_successfully'),
            new PaginatedSurveyResource($surveys),
            200
        );
    }

    public function show($id)
    {
        $survey = $this->surveyRepository->find($id);

        if (!$survey) {
            return ApiResponse::error(__('survey.survey_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('survey.data_fetched_successfully'),
            new SurveyResource($survey),
            200
        );
    }

    public function store(StoreSurveyRequest $request)
    {
        $dto =StoreSurveyDTO::fromRequest($request);
        $survey = $this->surveyRepository->create($dto);
        if (!$survey['status'] === 'survey_not_created') {
            return ApiResponse::error(__('survey.survey_not_created'),[], 200);
        }
        return ApiResponse::success(__('survey.survey_created_successfully'),[], 201);
    }

    public function update(UpdateSurveyRequest $request, $id)
    {
        $dto = UpdateSurveyDTO::fromRequest($request);
        $survey = $this->surveyRepository->update($id, $dto->toArray());
        if ($survey['status'] === 'survey_not_found') {
            return ApiResponse::error(__('survey.survey_not_found'),[], 200);
        }
        return ApiResponse::success(__('survey.data_fetched_successfully'),[], 200);
    }

    public function destroy($id)
    {
        $survey=$this->surveyRepository->delete($id);
        if ($survey['status'] === 'survey_not_found') {
            return ApiResponse::error(
                __('survey.survey_not_found'),
                [],
                200
            );
        }
        return ApiResponse::success(
            __('survey.survey_deleted_successfully'),
            [],
            200
        );
    }
}
