<?php

namespace App\Http\Resources\V1\CRM\Campaign;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'value' => $this->value,
            'min_booking' => (float) $this->min_booking,
        ];
    }
}
