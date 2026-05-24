<?php

namespace App\Http\Resources\V1\CRM\Feature;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->localized_name,
            // 'hotel_id' => $this->hotel_id, //category already has hotel_id
            'logo' => $this->logo
        ];
    }
}