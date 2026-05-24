<?php

namespace App\Http\Repository\V1\CRM\Feedback;

use App\Models\Feedback;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;

class FeedbackRepository
{
    public function index(array $filters): LengthAwarePaginator
    {
        $query = Feedback::with(['guest', 'booking', 'serviceReservation']);

        $obligatoryHotelId = current_hotel_id();
        $optionalHotelId   = $filters['hotel_id'] ?? null;

        if ($obligatoryHotelId && $optionalHotelId && $obligatoryHotelId != $optionalHotelId) {
            throw new AuthorizationException(__('feedback.unauthorized_hotel_filter'));
        }

        if ($obligatoryHotelId) {
            $query->where(function ($q) use ($obligatoryHotelId) {
                $q->whereHas('booking.room.roomType.category', fn($sub) => $sub->where('hotel_id', $obligatoryHotelId))
                ->orWhereHas('serviceReservation.service', fn($sub) => $sub->where('hotel_id', $obligatoryHotelId));
            });
        } elseif ($optionalHotelId) {
            $query->where(function ($q) use ($optionalHotelId) {
                $q->whereHas('booking.room.roomType.category', fn($sub) => $sub->where('hotel_id', $optionalHotelId))
                ->orWhereHas('serviceReservation.service', fn($sub) => $sub->where('hotel_id', $optionalHotelId));
            });
        }

        if (!empty($filters['guest_id'])) {
            $query->where('guest_id', $filters['guest_id']);
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
        return $query->paginate((int) $perPage);
    }

    public function find(int $id): Feedback
    {
        return Feedback::with(['guest', 'booking', 'serviceReservation'])
            ->where('id', $id)
            ->when(current_hotel_id(), function ($q, $hotelId) {
                $q->where(function ($q) use ($hotelId) {
                    $q->whereHas('booking.room.roomType.category', fn($sub) => $sub->where('hotel_id', $hotelId))
                      ->orWhereHas('serviceReservation.service', fn($sub) => $sub->where('hotel_id', $hotelId));
                });
            })
            ->firstOrFail();
    }

    public function getByServiceReservation(int $serviceReservationId): Feedback
    {
        return Feedback::with(['guest', 'serviceReservation.service'])
            ->where('service_reservation_id', $serviceReservationId)
            ->when(current_hotel_id(), function ($q, $hotelId) {
                $q->whereHas('serviceReservation.service', fn($sub) => $sub->where('hotel_id', $hotelId));
            })
            ->firstOrFail();
    }

    public function getByBooking(int $bookingId): Feedback
    {
        return Feedback::with(['guest', 'booking.room.roomType.category'])
            ->where('booking_id', $bookingId)
            ->when(current_hotel_id(), function ($q, $hotelId) {
                $q->whereHas('booking.room.roomType.category', fn($sub) => $sub->where('hotel_id', $hotelId));
            })
            ->firstOrFail();
    }
}
