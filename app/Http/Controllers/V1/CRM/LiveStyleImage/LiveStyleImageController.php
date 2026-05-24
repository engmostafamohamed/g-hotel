<?php

namespace App\Http\Controllers\V1\CRM\LiveStyleImage;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\CRM\LiveStyleImage\UpdateLiveStyleImageRequest;
use App\Http\Requests\V1\CRM\LiveStyleImage\LiveStyleImageReqest;
use App\Http\Requests\V1\CRM\LiveStyleImage\StoreLiveStyleImageRequest;
use App\Http\Repository\V1\CRM\LiveStyleImage\LiveStyleImageRepository;
use App\Http\Resources\V1\CRM\LiveStyle\LiveStyleImageResource;
use App\Http\Resources\V1\CRM\LiveStyle\PaginatedLiveStyleImageResource;
class LiveStyleImageController extends Controller
{
    protected $LiveStyleImage;

    public function __construct(LiveStyleImageRepository $LiveStyleImageRepository)
    {
        $this->LiveStyleImage = $LiveStyleImageRepository;
    }
    public function showAllLiveStyleImages(LiveStyleImageReqest  $request)
    {
        $result= $this->LiveStyleImage->showAllLiveStyleImagesRepository($request);
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('liveStyleImage.data_not_found'), [], 200);
        }
        if ($result['status'] === 'invalid_per_page') {
            return ApiResponse::error(__('liveStyleImage.invalid_per_page'), [], 400);
        }

        // return ApiResponse::success(
        //     __('liveStyleImage.data_fetched_successfully'),
        //     LiveStyleImageResource::collection($result['liveStyleImages']) ,
        //     200
        // );
        return ApiResponse::success(
            __('liveStyleImage.data_fetched_successfully'),
            new PaginatedLiveStyleImageResource($result['liveStyleImages']),
            200
        );

    }
    public function showLiveStyleImage(LiveStyleImageReqest  $request,$id)
    {
        $result= $this->LiveStyleImage->showLiveStyleImageRepository($id,$request);
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('liveStyleImage.data_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('liveStyleImage.data_fetched_successfully'),
             new LiveStyleImageResource($result['liveStyleImage']),
            200
        );
    }
    public function addLiveStyleImage(StoreLiveStyleImageRequest  $request)
    {
        $result= $this->LiveStyleImage->storeLiveStyleImageRepository($request);
        if ($result['status'] === 'image_not_found') {
            return ApiResponse::error(__('liveStyleImage.image_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('liveStyleImage.data_added_successfully'),
            [],
            201
        );
    }
    public function updateLiveStyleImage(UpdateLiveStyleImageRequest $request, $id)
    {
        $result= $this->LiveStyleImage->updateLiveStyleImageRepository($id,$request);
        if ($result['status'] === 'image_not_found') {
            return ApiResponse::error(__('liveStyleImage.image_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('liveStyleImage.data_updated_successfully'),
            [],
            200
        );

    }
    public function deleteLiveStyleImage(LiveStyleImageReqest  $request,$id){
        $result= $this->LiveStyleImage->deleteLiveStyleImageRepository($request,$id);
        if ($result['status'] === 'image_not_found') {
            return ApiResponse::error(__('liveStyleImage.live_style_image_id_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('liveStyleImage.data_deleted_successfully'),
            [],
            200
        );
    }
}
