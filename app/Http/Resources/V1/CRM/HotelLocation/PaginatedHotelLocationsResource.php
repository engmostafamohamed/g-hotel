<?php

namespace App\Http\Resources\V1\CRM\HotelLocation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginatedHotelLocationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => collect($this->items())->map(function ($hotel) {
                return [
                    'property_id' => $hotel->id,
                    'display_name' => $hotel->display_name,
                    'default_language' => $hotel->default_language,
                    'supported_currencies' => ['USD', 'EGP'],
                    'default_currency' => $hotel->default_currency,
                    'supported_languages' => ['en', 'ar'],
                    'timezone' => $hotel->timezone,
                    'lat' => $hotel->lat,
                    'long' => $hotel->long,
                    'hotel_video_url' => $hotel->hotel_video_url,
                    'location_name' => $hotel->location_name,
                    'address' => $hotel->address,
                    'version' => 1,
                    'services' => $hotel->services,
                    'restaurants' => $hotel->restaurants,
                    'employees' => $hotel->employees,
                    'config_status' => $hotel->is_active ? 'active' : 'inactive',
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
