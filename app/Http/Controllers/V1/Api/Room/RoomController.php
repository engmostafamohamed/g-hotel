<?php

namespace App\Http\Controllers\V1\Api\Room;

use App\DataTransferObjects\RoomDTOs\BulkRoomDTO;
use App\DataTransferObjects\RoomDTOs\Api\RoomDTO;
use App\DataTransferObjects\RoomDTOs\Api\BookRoomDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Api\Room\RoomResource;
use App\Http\Resources\V1\Api\Room\PaginatedRoomResource;
use App\Http\Resources\V1\CRM\Room\RoomResourceWithTypeAndCategory;
use App\Services\CRM\RoomService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use App\Http\Requests\V1\Api\Room\RoomRequest;
use App\Http\Requests\V1\Api\Room\BookRoomRequest;
use App\Http\Repository\V1\Api\Room\RoomRepository;
use App\Http\Resources\V1\Api\Room\RoomFilterResource;
use App\Services\V1\Api\Room\RoomService as RoomRoomService;
use Illuminate\Auth\Access\AuthorizationException;

class RoomController extends Controller
{
    public function __construct(private RoomRepository $roomRepository, private RoomRoomService $roomService) {}

    public function showRoom(RoomRequest $request)
    {
        try {
            $roomTypes = $this->roomRepository->findRooms(RoomDTO::fromRequest($request));

            if ($roomTypes->isEmpty()) {
                return ApiResponse::success(__('room.no_available_rooms'), [], 200);
            }

            return ApiResponse::success(
                __('room.available_rooms_fetched'),
                new PaginatedRoomResource($roomTypes),200
            );

        } catch (AuthorizationException $e) {
            return ApiResponse::error(__('room.hotel_context_required'), [], 403);

        } catch (\Throwable $e) {
            return ApiResponse::error(__('room.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function bookRoom(BookRoomRequest $request)
    {
        $result= $this->roomRepository->bookRoom(BookRoomDTO::fromRequest( $request));
        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('room.error_happend'), [$result['debug']], 500);
        }
        if ($result['status'] === 'no_available_rooms') {
            return ApiResponse::error(__('room.no_available_rooms'), [], 200);
        }
        if ($result['status'] === 'unauthorized') {
            return ApiResponse::error(__('room.hotel_context_required'), [], 403);
        }
        return ApiResponse::success(
            __('room.room_booked_successfully'),
            $result,
            200
        );
    }

    public function getRoomFilters()
    {
        try {
            $filters = $this->roomService->getFilters();

            return ApiResponse::success(
                __('room.filter_fetched'),
                new RoomFilterResource($filters),
                200
            );
        } catch (AuthorizationException $e) {
            return ApiResponse::error(__('room.hotel_context_required'), [], 403);

        } catch (Throwable $e) {
            return ApiResponse::error(__('room.unexpected_filter_fetch'), [$e->getMessage()], 500);
        }
    }
}
