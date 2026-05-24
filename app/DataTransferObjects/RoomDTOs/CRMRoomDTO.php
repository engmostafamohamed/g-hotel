<?php

namespace App\DataTransferObjects\RoomDTOs;

use App\Http\Requests\V1\CRM\Room\StoreRoomRequest;
use App\Http\Requests\V1\CRM\Room\UpdateRoomRequest;

class CRMRoomDTO
{
    public function __construct(
        public ?string $room_number,
        public ?int $room_type_id
    ) {}

    public static function fromRequest(StoreRoomRequest|UpdateRoomRequest $request): self
    {
        return new self(
            room_number: $request->input('room_number'),
            room_type_id: (int) $request->input('room_type_id'),
        );
    }
}