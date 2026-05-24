<?php

namespace App\Http\Controllers\V1\Api\StaticPages;
use App\Http\Resources\V1\CRM\StaticPageResource;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repository\V1\Api\StaticPages\AboutRepository;
class AboutController extends Controller
{
    protected $about;

    // public function __construct(staticPageRepository $aboutRepository)
    // {
    //     $this->about = $aboutRepository;
    // }
    // public function showAbout(Request  $request)
    // {
    //     $result= $this->about->getAboutPage($request);
    //     if ($result['status'] === 'not_found') {
    //         return ApiResponse::error(__('staticPages.data_not_found'), [], 404);
    //     }
    //     return ApiResponse::success(
    //         __('staticPages.data_fetched_successfully'),
    //         new StaticPageResource($result),
    //         200
    //     );
    // }
    // public function addAbout(Request  $request)
    // {
    //     $result= $this->about->storeAboutRepository($request);
    //     if ($result['status'] === 'not_found') {
    //         return ApiResponse::error(__('staticPages.data_not_found'), [], 404);
    //     }
    //     return ApiResponse::success(
    //         __('staticPages.data_added_successfully'),
    //         [],
    //         200
    //     );
    // }
}
