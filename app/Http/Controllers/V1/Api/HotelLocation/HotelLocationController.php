<?php

namespace App\Http\Controllers\V1\Api\HotelLocation;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Api\HotelLocation\PaginatedListHotelLocationsResource;
use App\Services\V1\Api\HotelLocation\HotelLocationService;
use Throwable;

class HotelLocationController extends Controller
{
    public function __construct(
        protected HotelLocationService $service
    ) {}

    public function index()
    {
        try{

            $hotelLocations = $this->service->index();
            return ApiResponse::success(
                __('hotel.fetched'),
                new PaginatedListHotelLocationsResource($hotelLocations),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('hotel.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }
}