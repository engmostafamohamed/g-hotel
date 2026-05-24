<?php

namespace App\Http\Controllers\V1\CRM\Room;

use App\DataTransferObjects\RoomDTOs\BulkRoomDTO;
use App\DataTransferObjects\RoomDTOs\CRMRoomDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Room\BulkCreateRoomRequest;
use App\Http\Requests\V1\CRM\Room\StoreRoomRequest;
use App\Http\Requests\V1\CRM\Room\UpdateRoomRequest;
use App\Http\Resources\V1\CRM\Room\RoomResource;
use App\Http\Resources\V1\CRM\Room\RoomResourceWithType;
use App\Http\Resources\V1\CRM\Room\RoomResourceWithTypeAndCategory;
use App\Http\Resources\V1\CRM\Room\PaginatedRoomResource;
use App\Services\V1\CRM\RoomService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use App\Http\Requests\V1\CRM\Room\RoomReqest;
use App\Http\Repository\V1\CRM\Room\RoomRepository;

class RoomController extends Controller
{
    public function __construct(private RoomService $service) {}

    public function index(Request $request)
    {
        try {
            $rooms = $this->service->list($request);
            return ApiResponse::success(
                __('room.fetched'),
                new PaginatedRoomResource($rooms),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('room.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    // public function indexUnpaginated(Request $request)
    // {
    //     try {
    //         $rooms = $this->service->listUnpaginated($request);
    //         return ApiResponse::success(__('room.fetched'), RoomResource::collection($rooms), 200);
    //     } catch (Throwable $e) {
    //         return ApiResponse::error(__('room.unexpected_fetch'), [$e->getMessage()], 500);
    //     }
    // }

    public function store(StoreRoomRequest $request)
    {
        try {
            $room = $this->service->create(CRMRoomDTO::fromRequest($request));
            return ApiResponse::success(
                __('room.created'),
                new RoomResourceWithTypeAndCategory($room),
                201
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('room.unexpected_create'), [$e->getMessage()], 500);
        }
    }

    public function bulkStore(BulkCreateRoomRequest $request)
    {
        try {
            $rooms = $this->service->bulkCreate(BulkRoomDTO::fromRequest($request));

            return ApiResponse::success(
                __('room.bulk_created'),
                new PaginatedRoomResource($rooms),
                201
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('room.unexpected_create'), [$e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $room = $this->service->find($id);
            return ApiResponse::success(
                __('room.fetched_single'),
                new RoomResourceWithTypeAndCategory($room),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('room.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('room.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function update(UpdateRoomRequest $request, int $id)
    {
        try {
            $room = $this->service->update($id, CRMRoomDTO::fromRequest($request));
            return ApiResponse::success(
                __('room.updated'),
                new RoomResourceWithTypeAndCategory($room),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('room.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('room.unexpected_update'), [$e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->service->delete($id);
            return ApiResponse::success(__('room.deleted'), [], 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('room.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('room.unexpected_delete'), [$e->getMessage()], 500);
        }
    }
}
