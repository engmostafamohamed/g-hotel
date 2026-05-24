<?php

namespace App\Http\Resources\V1\CRM\Feedback;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'guest' => $this->whenLoaded('guest', function () {
                return [
                    'id'   => $this->guest->id,
                    'name' => trim($this->guest->first_name . ' ' . $this->guest->last_name),
                ];
            }),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'booking' => $this->whenLoaded('booking', function () {
                return [
                    'id' => $this->booking->id,
                ];
            }),
            'service_reservation' => $this->whenLoaded('serviceReservation', function () {
                return [
                    'id'          => $this->serviceReservation->id,
                    'service_name'=> $this->serviceReservation->service->localized_name,
                ];
            }),
            'created_at' => $this->created_at,
        ];
    }
}
