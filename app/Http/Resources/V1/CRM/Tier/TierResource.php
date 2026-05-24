<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\CRM\Service\ServiceResource;
class TierResource extends JsonResource
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
            'min_nights' => $this->min_nights,
            'tier_name' => $this->getTranslations('tier_name'),
            'content' => $this->getTranslations('content'),
            'tier_value' => $this->tier_value,
            'deleted_at' => $this->deleted_at,
            'services' => new ServiceResource($this->whenLoaded('services'))
        ];
    }
}
