<?php

namespace App\Http\Resources\V1\Api\HotelLocation;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelLocationResource extends JsonResource
{
    public function toArray($request): array
    {
        // Extract first two lifestyle gifs
        $gifs = $this->liveStyleImages->pluck('images_url')->flatten();

        return [
            'id' => $this->id,
            'property_code' => $this->property_code,
            'display_name' => $this->display_name,
            'default_language' => $this->default_language,
            'default_currency' => $this->default_currency,
            'timezone' => $this->timezone,
            'is_active' => $this->is_active,
            'lat' => $this->lat,
            'long' => $this->long,
            'location_name' => $this->location_name,
            'address' => $this->getTranslation('address', app()->getLocale()),
            'videos' => $this->hotel_video_url,
            'gif_1' => $gifs->get(0) ?? null,
            'gif_2' => $gifs->get(1) ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
