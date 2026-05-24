<?php

namespace App\Http\Resources\V1\CRM\LiveStyle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveStyleImageResource extends JsonResource
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
            'caption' => $this->getTranslation('caption', app()->getLocale()),
            'images_url' => $this->images_url,
            'hotel_id' => $this->hotel_id,
        ];
    }
}
