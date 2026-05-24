<?php

namespace App\Services\V1\CRM\Booking;

use App\DataTransferObjects\V1\CRM\Booking\BookingHistoryDTO;
use App\DataTransferObjects\V1\CRM\Booking\BookRoomDTO;
use App\Http\Repository\V1\CRM\Booking\BookingRepository;
use App\Http\Repository\V1\CRM\RoomType\RoomTypeRepository;
use GuzzleHttp\Psr7\Request;
use Illuminate\Auth\Access\AuthorizationException;
use InvalidArgumentException;

class BookingService
{
    public function __construct(
        private BookingRepository $repository,
        private RoomTypeRepository $roomTypeRepository
    ) {}

    public function getTotalNightsByGuest(int $guestId)
    {
        return $this->repository->getTotalNightsByGuest($guestId);
    }

    /**
     * Create a booking that may include multiple room types
     */
    public function createBookingForGuest(BookRoomDTO $dto)
    {
        $employeeId   = auth('employee')->id();
        $hotelContext = current_hotel_id();

        // Validate hotel context consistency
        if (!$hotelContext && !$dto->hotel_id) {
            throw new InvalidArgumentException(__('booking.hotel_id_missing'));
        }

        if ($hotelContext && $dto->hotel_id && $hotelContext !== (int) $dto->hotel_id) {
            throw new AuthorizationException(__('booking.hotel_id_mismatch'));
        }

        // Determine the final hotel ID
        $finalHotelId = $hotelContext ?? (int) $dto->hotel_id;
        $dto->hotel_id = $finalHotelId;

        // Validate that all room types belong to the same hotel
        foreach ($dto->roomTypes as $roomTypeData) {
            $roomType = $this->roomTypeRepository->find($roomTypeData['id']);

            if ((int) $roomType->category->hotel_id !== $finalHotelId) {
                throw new AuthorizationException(__('booking.room_type_hotel_mismatch'));
            }
        }

        // Delegate core booking logic to repository
        return $this->repository->createBookingForGuest($dto, $employeeId);
    }

    public function getBookings( BookingHistoryDTO $dto)
    {
        return $this->repository->getFilteredBookings(
            $dto->hotel_id,
            $dto->guest_id,
            $dto->from_date,
            $dto->to_date,
            $dto->sort ?? 'desc',
            $dto->per_page ?? 10
        );
    }
}
