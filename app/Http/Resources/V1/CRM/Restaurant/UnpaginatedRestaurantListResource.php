<?php

namespace App\Http\Resources\V1\CRM\Restaurant;

use Illuminate\Http\Resources\Json\JsonResource;

class UnpaginatedRestaurantListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->localized_name,
            'cuisine' => $this->localizedCuisine,
            'hotel_id' => $this->hotel_id,
        ];
    }
}
