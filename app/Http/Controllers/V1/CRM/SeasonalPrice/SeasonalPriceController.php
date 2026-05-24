<?php

namespace App\Http\Controllers\V1\CRM\SeasonalPrice;

use App\DataTransferObjects\SeasonalPriceDTOs\SeasonalPriceDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\SeasonalPrice\StoreSeasonalPriceRequest;
use App\Http\Requests\V1\CRM\SeasonalPrice\UpdateSeasonalPriceRequest;
use App\Http\Resources\V1\CRM\SeasonalPrice\SeasonalPriceResource;
use App\Services\V1\CRM\SeasonalPriceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class SeasonalPriceController extends Controller
{
    public function __construct(private SeasonalPriceService $service) {}

    public function indexByRoomType(int $roomTypeId, Request $request)
    {
        try {
            $seasonalPrices = $this->service->getByRoomTypeId($roomTypeId, $request);

            return ApiResponse::success(
                __('seasonalPrice.fetched'),
                SeasonalPriceResource::collection($seasonalPrices),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('seasonalPrice.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $seasonalPrice = $this->service->find($id);

            return ApiResponse::success(
                __('seasonalPrice.fetched_single'),
                new SeasonalPriceResource($seasonalPrice),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('seasonalPrice.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('seasonalPrice.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function store(StoreSeasonalPriceRequest $request)
    {
        try {
            $seasonalPrice = $this->service->create(SeasonalPriceDTO::fromRequest($request));

            return ApiResponse::success(
                __('seasonalPrice.created'),
                new SeasonalPriceResource($seasonalPrice),
                201
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('seasonalPrice.unexpected_create'), [$e->getMessage()], 500);
        }
    }

    public function update(int $id, UpdateSeasonalPriceRequest $request)
    {
        try {
            $updated = $this->service->update($id, SeasonalPriceDTO::fromRequest($request));

            return ApiResponse::success(
                __('seasonalPrice.updated'),
                new SeasonalPriceResource($updated),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('seasonalPrice.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('seasonalPrice.unexpected_update'), [$e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->service->delete($id);

            return ApiResponse::success(__('seasonalPrice.deleted'), [], 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('seasonalPrice.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('seasonalPrice.unexpected_delete'), [$e->getMessage()], 500);
        }
    }
}