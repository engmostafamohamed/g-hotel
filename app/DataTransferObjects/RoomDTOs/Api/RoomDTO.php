<?php

namespace App\DataTransferObjects\RoomDTOs\Api;

use Illuminate\Http\Request;

class RoomDTO
{
    public function __construct(
        // public int $hotel_id,
        public string $from_date,
        public string $to_date,
        public int $adults,
        public ?int $children = 0,
        public ?array $room_type_ids = null,
        public ?array $room_view_ids = null,
        public ?array $feature_ids = null,
        public ?float $min_price = null,
        public ?float $max_price = null,
        public ?string $sort_by = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            // hotel_id: $request->input('hotel_id'),
            from_date: $request->input('from_date'),
            to_date: $request->input('to_date'),
            adults: (int) $request->input('adults'),
            children: (int) $request->input('children', 0),
            room_type_ids: $request->input('room_type_ids', []),
            room_view_ids: $request->input('room_view_ids', []),
            feature_ids: $request->input('feature_ids', []),
            min_price: $request->input('min_price'),
            max_price: $request->input('max_price'),
            sort_by: $request->input('sort_by')
        );
    }

    public function getSort(): array
    {
        return match ($this->sort_by) {
            'price_low_to_high'  => ['field' => 'final_price', 'dir' => 'asc'],
            'price_high_to_low'  => ['field' => 'final_price', 'dir' => 'desc'],
            'rating_high_to_low' => ['field' => 'rating', 'dir' => 'desc'],
            'rating_low_to_high' => ['field' => 'rating', 'dir' => 'asc'],
            default              => ['field' => 'final_price', 'dir' => 'asc'],
        };
    }
}