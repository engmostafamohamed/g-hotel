<?php

namespace App\DataTransferObjects\RoomDTOs;

use App\Http\Requests\V1\CRM\Room\BulkCreateRoomRequest;

class BulkRoomDTO
{
    public function __construct(
        public ?int $room_type_id,
        public ?array $room_numbers
    ) {}

    public static function fromRequest(BulkCreateRoomRequest $request): self
    {
        return new self(
            room_type_id: $request->input('room_type_id'),
            room_numbers: $request->input('room_numbers')
        );
    }
}
