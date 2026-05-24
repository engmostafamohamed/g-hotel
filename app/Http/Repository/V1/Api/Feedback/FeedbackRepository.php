<?php

namespace App\Http\Repository\V1\Api\Feedback;

use App\DataTransferObjects\V1\Api\Feedback\FeedbackDTO;
use App\Models\Feedback;
use Illuminate\Pagination\LengthAwarePaginator;

class FeedbackRepository
{
    public function create(FeedbackDTO $dto): Feedback
    {
        $feedback = Feedback::create((array) $dto);

        return $feedback->load(['booking', 'serviceReservation.service']);
    }

    public function update(Feedback $feedback, FeedbackDTO $dto): Feedback
    {
        $feedback->update(array_filter((array) $dto));

        return $feedback->load(['booking', 'serviceReservation.service']);
    }


    public function index(int $guestId, array $filters): LengthAwarePaginator
    {
        $query = Feedback::with(['booking', 'serviceReservation'])
            ->where('guest_id', $guestId);

        if ($obligatoryHotelId = current_hotel_id()) {
            $query->where(function ($q) use ($obligatoryHotelId) {
                $q->whereHas('booking.room.roomType.category', fn($sub) => $sub->where('hotel_id', $obligatoryHotelId))
                  ->orWhereHas('serviceReservation.service', fn($sub) => $sub->where('hotel_id', $obligatoryHotelId));
            });
        }

        if (!empty($filters['service_id'])) {
            $query->whereHas('serviceReservation', fn($q) => $q->where('service_id', $filters['service_id']));
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        $perPage = request()->query('per_page', 10);

        return $query->paginate(request()->get('per_page', (int) $perPage));
    }

    public function find(int $id, int $guestId): Feedback
    {
        return Feedback::with(['booking', 'serviceReservation'])
            ->where('id', $id)
            ->where('guest_id', $guestId)
            ->when(current_hotel_id(), function ($q, $hotelId) {
                $q->where(function ($q) use ($hotelId) {
                    $q->whereHas('booking.room.roomType.category', fn($sub) => $sub->where('hotel_id', $hotelId))
                      ->orWhereHas('serviceReservation.service', fn($sub) => $sub->where('hotel_id', $hotelId));
                });
            })
            ->firstOrFail();
    }

    public function getByServiceReservation(int $serviceReservationId, int $guestId): Feedback
    {
        return Feedback::with(['booking', 'serviceReservation'])
            ->where('service_reservation_id', $serviceReservationId)
            ->where('guest_id', $guestId)
            ->when(current_hotel_id(), function ($q, $hotelId) {
                $q->where(function ($q) use ($hotelId) {
                    $q->WhereHas('serviceReservation.service', fn($sub) => $sub->where('hotel_id', $hotelId));
                });
            })
            ->firstOrFail();
    }

    public function getByBooking(int $bookingId, int $guestId): Feedback
    {
        return Feedback::with(['guest', 'booking.room.roomType.category'])
            ->where('booking_id', $bookingId)
            ->where('guest_id', $guestId)
            ->when(current_hotel_id(), function ($q, $hotelId) {
                $q->whereHas('booking.room.roomType.category', fn($sub) => $sub->where('hotel_id', $hotelId));
            })
            ->firstOrFail();
    }
}
