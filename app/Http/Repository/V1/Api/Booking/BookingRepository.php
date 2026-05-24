<?php

namespace App\Http\Repository\V1\Api\Booking;

use App\Models\Booking;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingRepository
{
    public function getFilteredBookings(
        ?int $hotelId,
        ?int $guestId,
        ?string $fromDate,
        ?string $toDate,
        string $sort = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Booking::with(['guest', 'rooms', 'hotel'])
            ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))
            ->when($guestId, fn($q) => $q->where('guest_id', $guestId))
            ->when(
                $fromDate && $toDate,
                fn($q) =>
                $q->whereBetween('booking_date', [$fromDate, $toDate])
            )
            ->orderBy('booking_date', $sort);

        return $query->paginate($perPage);
    }
}
