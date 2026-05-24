<?php

namespace App\Http\Resources\V1\CRM\RestaurantMenu;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllMenusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'restaurant_id' => $this->id,
            'name' => $this->localized_name,
            'cuisine' => $this->localized_cuisine,
            'hotel_location' => [
                'id' => $this->hotelLocation?->id,
                'name' => $this->hotelLocation?->display_name,
                'code' => $this->hotelLocation?->property_code,
            ],
            'categories' => $this->menuCategories->map(function ($category) {
                return [
                    'category_id' => $category->id,
                    'name' => $category->name,
                    'items' => $category->menuItems->map(function ($item) {
                        return [
                            'item_id' => $item->id,
                            'name' => $item->name,
                            'description' => $item->description,
                            'image_path' =>$item->image_path,
                            'price' => $item->price,
                            'dietary_tags' => $item->dietary_tags,
                        ];
                    }),
                ];
            }),
        ];
    }
}
