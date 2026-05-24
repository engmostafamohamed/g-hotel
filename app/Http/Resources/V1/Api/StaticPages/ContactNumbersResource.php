<?php

namespace App\Http\Resources\V1\Api\StaticPages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactNumbersResource extends JsonResource
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
            'hotel_location_id' => $this->hotel_location_id,
            'label' => $this->getTranslation('label',app()->getLocale()),
            'value' => $this->value,

        ];
    }
}
