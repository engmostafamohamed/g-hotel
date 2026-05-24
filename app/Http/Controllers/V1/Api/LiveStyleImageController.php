<?php

namespace App\Http\Controllers\V1\Api;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\Api\LiveStyleImageReqest;
use App\Http\Repository\V1\Api\LiveStyleImageRepository;
class LiveStyleImageController extends Controller
{
     protected $liveStyleImage;

    public function __construct(LiveStyleImageRepository $liveStyleImageRepository)
    {
        $this->liveStyleImage = $liveStyleImageRepository;
    }
    public function showLiveStyleImage(Request  $request)
    {
        $result= $this->liveStyleImage->showLiveStyleImageRepository($request);
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('LiveStyleImage.data_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('liveStyleImage.data_fetched_successfully'),
            $result['liveStyleImages'],
            200
        );
    }
    public function addLiveStyleImage(LiveStyleImageReqest $request)
    {
        $result = $this->liveStyleImage->storeLiveStyleImageRepository($request);

        if ($result['status'] === 'image_not_found') {
            return ApiResponse::error(__('liveStyleImage.image_not_found'), [], 200);
        }

        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('liveStyleImage.error_happend'), [], 500);
        }

        // success
        return ApiResponse::success(
            __('liveStyleImage.data_added_successfully'),
            [],
            201
        );
    }
}
