<?php

namespace App\Http\Resources\V1\CRM\HotelLocation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'property_id' => $this->id,
            'display_name' => $this->display_name,
            'default_language' => $this->default_language,
            'supported_currencies' => ['USD', 'EGP'],
            'default_currency' => $this->default_currency,
            'supported_languages' => ['en', 'ar'],
            'timezone' => $this->timezone,
            'lat' => $this->lat,
            'long' => $this->long,
            'hotel_video_url' => $this->hotel_video_url,
            'location_name' => $this->location_name,
            'address' => $this->address,
            'version' => 1,
            'services'=> $this->services,
            'restaurants'=> $this->restaurants,
            'employees' => $this->employees,
            'config_status' => $this->is_active ? 'active' : 'inactive',
        ];
    }
}
