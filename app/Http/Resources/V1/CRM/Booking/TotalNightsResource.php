<?php

namespace App\Http\Resources\V1\CRM\Booking;

use Illuminate\Http\Resources\Json\JsonResource;

class TotalNightsResource extends JsonResource
{
    public function toArray($request): array
    {
        // $this is a Collection (hotel_id => total_nights)
        return $this->resource->map(function ($nights, $hotelId) {
            return [
                'hotel_id' => $hotelId,
                'total_nights' => (int) $nights,
            ];
        })->values()->all();
    }
}
