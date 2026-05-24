<?php

namespace App\DataTransferObjects\RoomTypeDTOs;

use App\Http\Requests\V1\CRM\RoomType\StoreRoomTypeRequest;
use App\Http\Requests\V1\CRM\RoomType\UpdateRoomTypeRequest;

class RoomTypeDTO
{
    public function __construct(
        public ?string $room_code,
        public ?array $name,
        public ?array $description,
        public ?float $base_price,
        public ?int $category_id,
        public ?array $views = [],
    ) {}

    public static function fromRequest(StoreRoomTypeRequest|UpdateRoomTypeRequest $request): self
    {
        return new self(
            room_code: $request->input('room_code'),
            name: $request->input('name'),
            description: $request->input('description'),
            base_price: $request->input('base_price'),
            category_id: $request->input('category_id'),
            views: $request->input('views', []),
        );
    }
}
