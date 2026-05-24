<?php

namespace App\Http\Resources\V1\CRM\Restaurant;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->localized_name,
            'cuisine' => $this->localized_cuisine,
            'hotel_id' => $this->hotel_id,
            'image_url' => $this->image_url,
            'schedules' => $this->schedules,
            // comment below line if menu details are not needed with restaurant data
            'menu_categories' => MenuCategoryResource::collection($this->menuCategories),
        ];
    }
}
