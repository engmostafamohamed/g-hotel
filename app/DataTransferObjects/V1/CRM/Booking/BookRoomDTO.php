<?php

namespace App\DataTransferObjects\V1\CRM\Booking;

use Illuminate\Http\Request;

class BookRoomDTO
{
    public function __construct(
        public ?int $hotel_id,
        public int $guest_id,
        public string $checkIn,
        public string $checkOut,
        public int $adults,
        public int $children,
        public array $roomTypes,  // array of ['id' => int, 'count' => int]
        // public ?array $upsells,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            hotel_id:  $request->input('hotel_id'),
            guest_id:  (int) $request->input('guest_id'),
            checkIn:   $request->input('dates.check_in'),
            checkOut:  $request->input('dates.check_out'),
            adults:    (int) $request->input('guests.adults'),
            children:  (int) $request->input('guests.children', 0),
            roomTypes: $request->input('room_types', []),
            // upsells:   $request->input('upsells', []),
        );
    }

    public function toArray(): array
    {
        return [
            'hotel_id'   => $this->hotel_id,
            'guest_id'   => $this->guest_id,
            'check_in'   => $this->checkIn,
            'check_out'  => $this->checkOut,
            'adults'     => $this->adults,
            'children'   => $this->children,
            'room_types' => $this->roomTypes,
            // 'upsells'    => $this->upsells,
        ];
    }
}
