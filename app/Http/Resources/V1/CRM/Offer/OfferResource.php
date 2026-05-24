<?php

namespace App\Http\Resources\V1\CRM\Offer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'type' => $this->type,
            'value' => $this->value,
            'inventory' => [
                'total' => $this->total_inventory,
                'per_guest' => $this->per_guest_inventory,
            ],
            'valid_dates' => [
                $this->start_date,
                $this->end_date,
            ],
            'service' => $this->whenLoaded('service', function () {
                return [
                    'id' => $this->service->id,
                    'name' => $this->service->name,
                    'price' => $this->service->price,
                ];
            }),
            'redemption_code' => $this->redemption_code,
            'created_at' => $this->created_at,
        ];
    }
}
