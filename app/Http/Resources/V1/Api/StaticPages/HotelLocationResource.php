<?php

namespace App\Http\Resources\V1\Api\StaticPages;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'property_code' => $this->property_code,
            'display_name' => $this->display_name,
            // 'default_language' => $this->default_language,
            // 'default_currency' => $this->default_currency,
            'timezone' => $this->timezone,
            'lat' => $this->lat,
            'long' => $this->long,
            'hotel_video_url' => $this->hotel_video_url,
            'location_name' => $this->location_name,
            'address' => $this->getTranslation('address',app()->getLocale()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
