<?php

namespace App\Http\Resources\V1\CRM\Booking;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'booking_id'    => $this['booking_id'] ?? null,
            'total_price'   => $this['total_price'] ?? 0,
            'total_nights'  => $this['total_nights'] ?? 0,
            'booked'        => $this['booked'] ?? [],
            'rooms'         => $this['rooms'] ?? [],
            'unavailable'   => $this['unavailable'] ?? [],
        ];
    }
}
