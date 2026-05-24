<?php

namespace App\Http\Resources\V1\CRM\Guest;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->first_name . ' ' . $this->last_name,
            'guest_title' => $this->guest_title,
            'profile_photo' => $this->profile_photo,
            'email' => $this->email,
            'phone_number' => $this->phone_no,
            'tier' => $this->tier,
            'member_since' => $this->member_since,
            'country' => $this->country,
            'city' => $this->city,
            'status' => $this->status,
            'total_stays' => 0,
            'order_history' => $this->serviceReservations,
            'preferences' => null,
            'life_time_value' => null, 
            'booking_history' => $this->serviceReservations,
            'total_nights_stayed' => 1,
            // 'location' => optional($this->city)->name,
            // 'last_stay' => optional($this->last_stay)->toDateString(), // Replace or override if needed
            'total_points' => $this->total_points,
        ];
    }
}
