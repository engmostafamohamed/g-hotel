<?php

namespace App\Http\Controllers\V1\Api\StaticPages;
use App\Http\Resources\V1\Api\StaticPages\StaticPagesResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Repository\V1\Api\StaticPages\StaticPageRepository;
class StaticPagesController extends Controller
{

    protected StaticPageRepository $staticPageRepository;

    public function __construct(StaticPageRepository $staticPageRepository)
    {
        $this->staticPageRepository = $staticPageRepository;
    }
    public function showAbout()
    {
        $result=$this->staticPageRepository->getAboutPage();
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('staticPages.data_not_found'), [], 200);
        }

        return ApiResponse::success(
            __('staticPages.data_fetched_successfully'),
            new StaticPagesResource($result['aboutData']),
            200
        );
    }
    public function showTermsAndCondition()
    {
        $result= $this->staticPageRepository->getTermsAndConditionsPage();
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('staticPages.data_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('staticPages.data_fetched_successfully'),
            new StaticPagesResource($result['termsAndConditionData']),
            200
        );
    }
    public function showPrivacyAndPolice(Request  $request)
    {
        $result= $this->staticPageRepository->getPrivacyAndPolicyPage();
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('staticPages.data_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('staticPages.data_fetched_successfully'),
            new StaticPagesResource($result['privacyAndPolicyData']),
            200
        );
    }
    public function showContactNumbers(Request  $request)
    {
        $result= $this->staticPageRepository->getPrivacyAndPolicyPage();
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('staticPages.data_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('staticPages.data_fetched_successfully'),
            new StaticPagesResource($result),
            200
        );
    }
}
