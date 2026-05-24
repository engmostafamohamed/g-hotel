<?php

namespace App\Services\V1\Api\Booking;

use App\DataTransferObjects\V1\Api\Booking\BookingHistoryDTO;
use App\Http\Repository\V1\CRM\Booking\BookingRepository;

class BookingService
{
    public function __construct(
        private BookingRepository $repository,
    ) {}

    public function getBookings(BookingHistoryDTO $dto)
    {
        $hotelId = current_hotel_id();

        return $this->repository->getFilteredBookings(
            $hotelId,
            auth('guest')->id(),
            $dto->from_date,
            $dto->to_date,
            $dto->sort ?? 'desc',
            $dto->per_page ?? 10
        );
    }
}
