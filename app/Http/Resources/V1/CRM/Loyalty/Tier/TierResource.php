<?php

namespace App\Http\Resources\V1\CRM\Loyalty\Tier;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\CRM\Service\ServiceResource;
class TierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'tier_id' => $this->id,
            // this used to work with spatie translatable package use (HasTranslations) in model
            // 'tier_name' => [
            //     'ar' => $this->getTranslation('tier_name', 'ar'),
            //     'en' => $this->getTranslation('tier_name', 'en'),
            // ],
            'code' => $this->code,
            'tier_name' => [
                'ar' => $this->tier_name['ar'] ?? null,
                'en' => $this->tier_name['en'] ?? null,
            ],
            'content' => [
                'ar' => $this->content['ar'] ?? null,
                'en' => $this->content['en'] ?? null,
            ],
            'tier_value' => $this->threshold,
            // 'services' => ServiceResource::collection($this->whenLoaded('services')),
        ];
    }
}

