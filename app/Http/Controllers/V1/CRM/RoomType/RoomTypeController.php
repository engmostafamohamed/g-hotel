<?php

namespace App\Http\Controllers\V1\CRM\RoomType;

use App\DataTransferObjects\RoomTypeDTOs\RoomTypeDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\RoomType\StoreRoomTypeRequest;
use App\Http\Requests\V1\CRM\RoomType\UpdateRoomTypeRequest;
use App\Http\Resources\V1\CRM\RoomType\PaginatedRoomTypeResource;
use App\Http\Resources\V1\CRM\RoomType\RoomTypeResource;
use App\Services\V1\CRM\RoomTypeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class RoomTypeController extends Controller
{
    public function __construct(private RoomTypeService $service) {}

    public function index(Request $request)
    {
        try {
            $roomTypes = $this->service->list($request);

            return ApiResponse::success(
                __('roomType.fetched'),
                new PaginatedRoomTypeResource($roomTypes),
                200
            );
        } catch (AuthorizationException $e) {
            return ApiResponse::error(__('roomType.unauthorized'), [$e->getMessage()], 403);
        } catch (Throwable $e) {
            return ApiResponse::error(__('roomType.unexpected'), [$e->getMessage()], 500);
        }
    }


    // public function indexUnpaginated(Request $request)
    // {
    //     try {
    //         $roomTypes = $this->service->listUnpaginated($request);
    //
    //         return ApiResponse::success(
    //             __('roomType.fetched'),
    //             RoomTypeResource::collection($roomTypes),
    //             200
    //         );
    //     } catch (Throwable $e) {
    //         return ApiResponse::error(__('roomType.unexpected'), [$e->getMessage()], 500);
    //     }
    // }

    public function store(StoreRoomTypeRequest $request)
    {
        try {
            $roomType = $this->service->create(RoomTypeDTO::fromRequest($request));

            return ApiResponse::success(
                __('roomType.created'),
                new RoomTypeResource($roomType),
                201
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('roomType.unexpected_create'), [$e->getMessage()], 500);
        }
    }

    public function update(UpdateRoomTypeRequest $request, int $id)
    {
        try {
            $roomType = $this->service->update($id, RoomTypeDTO::fromRequest($request));

            return ApiResponse::success(
                __('roomType.updated'),
                new RoomTypeResource($roomType),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('roomType.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('roomType.unexpected_update'), [$e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->service->delete($id);

            return ApiResponse::success(__('roomType.deleted'), [], 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('roomType.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('roomType.unexpected_delete'), [$e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $roomType = $this->service->find($id);

            return ApiResponse::success(
                __('roomType.fetched_single'),
                new RoomTypeResource($roomType),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('roomType.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('roomType.unexpected'), [$e->getMessage()], 500);
        }
    }
}