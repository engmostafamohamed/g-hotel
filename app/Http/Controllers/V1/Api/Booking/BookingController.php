<?php

namespace App\Http\Controllers\V1\Api\Booking;

use App\DataTransferObjects\V1\Api\Booking\BookingHistoryDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\Booking\BookingHistoryRequest;
use App\Http\Resources\V1\Api\Booking\ListBookingResource;
use App\Services\V1\Api\Booking\BookingService;
use Throwable;

class BookingController extends Controller
{
    public function __construct(private BookingService $service) {}

    public function index(BookingHistoryRequest $request)
    {
        try {
            $bookings = $this->service->getBookings(BookingHistoryDTO::fromRequest($request));
            return ApiResponse::success(
                __('booking.bookings_fetched_successfully'),
                ListBookingResource::collection($bookings),
                200,
                $bookings
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('booking.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }
}
