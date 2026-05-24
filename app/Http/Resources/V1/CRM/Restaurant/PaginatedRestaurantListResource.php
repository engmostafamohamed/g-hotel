<?php

namespace App\Http\Resources\V1\CRM\Restaurant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedRestaurantListResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
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
