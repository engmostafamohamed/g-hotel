<?php

namespace App\DataTransferObjects\RestaurantDTOs;

use App\Http\Requests\V1\CRM\Restaurant\RestaurantRequest;

class AvailabilityDTO
{
    // public ?string $imagePath = null;

    public function __construct(
        public ?bool $in_dining,
        public ?bool $room_service,
        public int $hotel_id,
        public int $restaurant_id,
        public array  $schedules ,
        public array  $exception_dates ,
    ) {}

    public static function fromRequest(RestaurantRequest $request): self
    {
        return new self(
            in_dining: $request->input('in_dining', []),
            room_service: $request->input('room_service', []),
            schedules: $request->input('schedules', []),
            exception_dates: $request->input('exception_dates', []),
            hotel_id: $request->input('hotel_id'),
            restaurant_id: $request->input('restaurant_id')
        );
    }
}
