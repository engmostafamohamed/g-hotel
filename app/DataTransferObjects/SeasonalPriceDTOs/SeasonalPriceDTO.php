<?php

namespace App\DataTransferObjects\SeasonalPriceDTOs;

use App\Http\Requests\V1\CRM\SeasonalPrice\StoreSeasonalPriceRequest;
use App\Http\Requests\V1\CRM\SeasonalPrice\UpdateSeasonalPriceRequest;
use Illuminate\Http\Request;

class SeasonalPriceDTO
{
    public function __construct(
        public ?int $room_type_id,
        public ?string $from,
        public ?string $to,
        public ?float $price,
        public ?float $points_discount,
    ) {}

    public static function fromRequest(StoreSeasonalPriceRequest|UpdateSeasonalPriceRequest $request): self
    {
        return new self(
            room_type_id: $request->room_type_id,
            from: $request->from,
            to: $request->to,
            price: $request->price,
            points_discount: $request->points_discount
        );
    }
}
