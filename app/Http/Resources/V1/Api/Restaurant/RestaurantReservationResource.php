<?php

namespace App\Http\Resources\V1\Api\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'restaurant_name' => $this->restaurant->getTranslation('name', app()->getLocale()),
            'restaurant_image' => $this->restaurant->image_url,
            'reservation_time' => $this->reservation_time,
            'order_type' => $this->order_type,
            'notes' => $this->notes,
            'guest_id' => $this->guest_id,

            'order' => RestaurantOrderResource::collection($this->restaurantOrders),
        ];
    }
}
