<?php

namespace App\Http\Resources\V1\Api\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantOrderResource extends JsonResource
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
            'menu_item_id' => $this->menu_item_id,
            'menu_item_name' => $this->menuItem->getTranslation('name', app()->getLocale()),
            'quantity' => $this->quantity,
            'price' => (float) $this->menuItem->price,
            'subtotal' => (float) $this->quantity * $this->menuItem->price,
        ];
    }
}
