<?php

namespace App\Http\Resources\V1\Api\HotelLocation;

use Illuminate\Http\Resources\Json\ResourceCollection;


class PaginatedListHotelLocationsResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => collect($this->items())->map(function ($hotelLocation) use ($request) {
                return (new HotelLocationResource($hotelLocation))->toArray($request);
            }),
            'pagination' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
