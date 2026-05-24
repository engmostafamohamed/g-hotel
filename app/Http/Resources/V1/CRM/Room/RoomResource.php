<?php

namespace App\Http\Resources\V1\CRM\Room;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'room_number' => $this->room_number,
            'name' => $this->roomType->localized_name,
            'hotel_id' => $this->roomType->category->hotel_id,
            'capacity' => [
                'max_adults'   => $this->roomType->category->max_adults,
                'max_children' => $this->roomType->category->max_children,
                'infants_allowed' => $this->roomType->category->infants_allowed,
            ],
            'price' => $this->roomType->active_seasonal_price->price ?? $this->roomType->base_price,
        ];
    }
}
