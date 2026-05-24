<?php
namespace App\Http\Resources\V1\Api\Restaurant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedRestaurantListResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => collect($this->items())->map(function ($restaurant) {
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->localized_name,
                    'cuisine' => $restaurant->localized_cuisine,
                    'hotel_id' => $restaurant->hotel_id,
                    'schedules' => $restaurant->schedules,
                    'exceptions' => $restaurant->exceptions,
                    'image_url' => $restaurant->image_url,
                    'menu_items' => MenuCategoryResource::collection(
                        $restaurant->menuCategories  ?? collect()
                    ),
                ];
            }),
            'pagination' => [
                'total' => $this->resource->total(),
                'per_page' => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
            ]
        ];
    }
}

