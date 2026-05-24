<?php

namespace App\Http\Resources\V1\CRM\Booking;

use App\Http\Resources\V1\CRM\Guest\GuestResource;
use App\Http\Resources\V1\CRM\Room\RoomResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ListBookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'hotel_id'        => $this->hotel_id,
            'guest'           => new GuestResource($this->whenLoaded('guest')),
            'rooms'           => RoomResource::collection($this->whenLoaded('rooms')),
            'booking_date'    => $this->booking_date,
            'arrival_date'    => $this->arrival_date,
            'departure_date'  => $this->departure_date,
            'total_price'     => $this->total_price,
            'loyalty_points'  => [
                'earned' => $this->loyalty_points_earned,
                'redeemed' => $this->loyalty_points_redeemed
            ],
            'checked_out'     => $this->checked_out,
            'created_by'      => $this->created_by,
        ];
    }
}
