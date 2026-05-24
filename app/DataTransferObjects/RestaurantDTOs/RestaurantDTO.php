<?php

namespace App\DataTransferObjects\RestaurantDTOs;

use App\Http\Requests\V1\CRM\Restaurant\StoreRestaurantRequest;
use App\Http\Requests\V1\CRM\Restaurant\UpdateRestaurantRequest;

class RestaurantDTO
{
    public ?string $imagePath = null;

    public function __construct(
        public ?array $name,
        public ?array $cuisine,
        public ?int $hotel_id,
        public mixed $image,
    ) {}

    public static function fromRequest(StoreRestaurantRequest|UpdateRestaurantRequest $request): self
    {
        return new self(
            name: $request->input('name', []),
            cuisine: $request->input('cuisine', []),
            hotel_id: $request->input('hotel_id'),
            image: $request->file('image')
        );
    }
}
