<?php

namespace App\Http\Resources\V1\CRM\RoomType;

use App\Http\Resources\V1\CRM\View\ViewResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        $activeSeasonalPrice = $this->seasonalPrices->first();

        return [
            'id' => $this->id,
            'room_code' => $this->room_code,
            'name' => $this->localized_name,
            'description' => $this->localized_description,
            'hotel_id' => $this->category->hotel_id,
            'base_price' => $this->base_price,
            'seasonal_price' => $activeSeasonalPrice ? [
                'from'            => $activeSeasonalPrice->from,
                'to'              => $activeSeasonalPrice->to,
                'price'           => $activeSeasonalPrice->price,
                'points_discount' => $activeSeasonalPrice->points_discount,
            ] : null,
            'views' => ViewResource::collection($this->whenLoaded('views')),
			'category' => new CategoryResource($this->whenLoaded('category')),
            'quantity' => $this->rooms()->count(),
            // add available_quantity of rooms of that type later. (after reservation module is done)
        ];
    }
}
