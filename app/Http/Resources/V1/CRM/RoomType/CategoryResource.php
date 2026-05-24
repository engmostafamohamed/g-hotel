<?php

namespace App\Http\Resources\V1\CRM\RoomType;

use App\Http\Resources\V1\CRM\Category\CategoryBedResource;
use App\Http\Resources\V1\CRM\Feature\FeatureResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->localized_name,
            'description' => $this->localized_description,
            'hotel_id' => $this->hotel_id,
            'images' => $this->images,
            'max_adults' => $this->max_adults,
            'max_children' => $this->max_children,
            'infants_allowed' => $this->infants_allowed,
            'bed_configuration' => CategoryBedResource::collection($this->whenLoaded('beds')),
            'policies' => $this->getLocalizedPoliciesAttribute(),
            'features' => FeatureResource::collection($this->whenLoaded('features'))
        ];
    }
}
