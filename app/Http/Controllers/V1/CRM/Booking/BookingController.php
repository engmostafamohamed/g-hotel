<?php

namespace App\Http\Controllers\V1\CRM\Booking;

use App\DataTransferObjects\V1\CRM\Booking\BookingHistoryDTO;
use App\DataTransferObjects\V1\CRM\Booking\BookRoomDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Booking\BookingHistoryRequest;
use App\Http\Requests\V1\CRM\Booking\BookRoomRequest;
use App\Http\Resources\V1\CRM\Booking\BookingResource;
use App\Http\Resources\V1\CRM\Booking\ListBookingResource;
use App\Http\Resources\V1\CRM\Booking\TotalNightsResource;
use App\Services\V1\CRM\Booking\BookingService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Throwable;

class BookingController extends Controller
{
    public function __construct(private BookingService $service) {}

    /**
     * Get total nights per guest
     */
    public function getTotalNightsByGuest(int $guestId)
    {
        try {
            $nights = $this->service->getTotalNightsByGuest($guestId);

            return ApiResponse::success(
                __('booking.total_nights_fetched'),
                new TotalNightsResource($nights),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('booking.guest_not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('booking.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    /**
     * Create a booking for a guest (multi-room-type supported)
     */
    public function createBookingForGuest(BookRoomRequest $request)
    {
        try {
            $dto = BookRoomDTO::fromRequest($request);
            $result = $this->service->createBookingForGuest($dto);

            if (isset($result['success']) && $result['success'] === false) {
                return ApiResponse::error($result['message'], $result['unavailable'], 200);
            }

            return ApiResponse::success(
                __('booking.booking_created_successfully'),
                new BookingResource($result),
                200
            );
        } catch (InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), [], 422);
        } catch (AuthorizationException $e) {
            return ApiResponse::error($e->getMessage(), [], 403);
        } catch (Throwable $e) {
            return ApiResponse::error(__('booking.error_happened'), [$e->getMessage()], 500);
        }
    }

    public function index(BookingHistoryRequest $request)
    {
        try {
            $bookings = $this->service->getBookings(BookingHistoryDTO::fromRequest($request));
            return ApiResponse::success(
                __('booking.bookings_fetched_successfully'),
                ListBookingResource::collection($bookings),
                200,
                $bookings // to include pagination meta
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('booking.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }
}
