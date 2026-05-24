<?php

namespace App\DataTransferObjects\RoomDTOs\Api;

use Illuminate\Http\Request;

class BookRoomDTO
{
    public function __construct(
        public int  $roomType_id,
        // public int  $room_id,
        public string  $checkIn,
        public string  $checkOut,
        public int     $adults,
        public int     $children,
        public int     $quantity,
        public ?array  $upsells = null,
        public int    $guest_id,
        public ?string $hotel_id,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            roomType_id:  $request->input('roomType_id'),
            // room_id:  $request->input('room_id'),
            checkIn:   $request->input('dates.check_in'),
            checkOut:  $request->input('dates.check_out'),
            adults:    (int) $request->input('guests.adults'),
            children:  (int) $request->input('guests.children', 0),
            quantity:  (int) $request->input('quantity', 1),
            upsells:   $request->input('upsells'),
            guest_id:    $request->input('guest_id'),
            hotel_id: $request->input('hotel_id'),
        );
    }

    public function toArray(): array
    {
        return [
            'room_type_id'   => $this->roomType_id,
            // 'room_id'        => $this->room_id,
            'check_in'    => $this->checkIn,
            'check_out'   => $this->checkOut,
            'adults'      => $this->adults,
            'children'    => $this->children,
            'quantity'    => $this->quantity,
            'upsells'     => $this->upsells,
            'guest_id'     => $this->guest_id,
            'hotel_id'  => $this->hotel_id,
        ];
    }
}
