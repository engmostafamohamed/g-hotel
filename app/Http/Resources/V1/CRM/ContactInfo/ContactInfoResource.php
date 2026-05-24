<?php

namespace App\Http\Resources\V1\CRM\ContactInfo;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'hotel_location_id' => $this->hotel_location_id,
            'type' => $this->type,
            'label' => $this->getTranslations('label'),
            'value' => $this->value
        ];
    }
}
