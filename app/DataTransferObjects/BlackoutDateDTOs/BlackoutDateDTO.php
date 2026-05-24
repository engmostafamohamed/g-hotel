<?php

namespace App\DataTransferObjects\BlackoutDateDTOs;

use App\Http\Requests\V1\CRM\BlackoutDate\StoreBlackoutDateRequest;
use App\Http\Requests\V1\CRM\BlackoutDate\UpdateBlackoutDateRequest;

class BlackoutDateDTO
{
    // public ?string $imagePath = null;

    public function __construct(
        public ?array $blackoutDate_name,
        public ?string $blackoutDate_start_date,
        public ?string $blackoutDate_end_date,
        public int $hotel_id,
        public ?array $category_ids,
        public ?bool $allow_existing_booking,
    ) {}

    public static function fromRequest(StoreBlackoutDateRequest|UpdateBlackoutDateRequest $request): self
    {
        return new self(
            blackoutDate_name: $request->input('blackoutDate_name'),
            blackoutDate_start_date: $request->input('blackoutDate_start_date'),
            blackoutDate_end_date: $request->input('blackoutDate_end_date'),
            hotel_id: $request->input('hotel_id'),
            category_ids: $request->input('category_ids'),
            allow_existing_booking: $request->input('allow_existing_booking')
        );
    }
}
