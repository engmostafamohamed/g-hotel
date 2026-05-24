<?php

namespace App\Http\Controllers\V1\Api\StaticPages;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\Api\locationPermissionReqest;
use App\Http\Resources\V1\Api\StaticPages\HotelLocationResource;
use App\Http\Repository\V1\Api\StaticPages\LocationPermissionRepository;
class LocationPermissionController extends Controller
{
    protected $locationPermission;

    public function __construct(LocationPermissionRepository $LocationPermissionRepository)
    {
        $this->locationPermission = $LocationPermissionRepository;
    }
    public function showLocationPermission(Request  $request)
    {
        $result= $this->locationPermission->showLocationPermissionRepository($request);
        if ($result['status'] === 'data_not_found') {
            return ApiResponse::error(__('validation.data_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('validation.data_fetched_successfully'),
            HotelLocationResource::collection($result['locationPermission']),
            200
        );
    }
    // public function addLocationPermission(LocationPermissionReqest  $request)
    // {
    //     $result= $this->locationPermission->storeLocationPermissionRepository($request);
    //     if ($result['status'] === 'not_found') {
    //         return ApiResponse::error(__('validation.data_not_found'), [], 404);
    //     }
    //     // if ($result['status'] === 'video_not_found') {
    //     //     return ApiResponse::error(__('validation.video_not_found'), [], 404);
    //     // }
    //     return ApiResponse::success(
    //         __('validation.data_added_successfully'),
    //         [],
    //         201
    //     );
    // }
}
