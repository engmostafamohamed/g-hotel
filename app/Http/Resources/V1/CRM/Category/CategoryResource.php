<?php

namespace App\Http\Resources\V1\CRM\Category;

use App\Http\Resources\V1\CRM\Feature\FeatureMiniResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->localized_name,
            'image_url' => $this->images,
            'hotel_id' => $this->hotel_id,
            'description' => $this->localized_description,
            'max_adults' => $this->max_adults,
            'max_children' => $this->max_children,
            'infants_allowed' => $this->infants_allowed,
            'features' => FeatureMiniResource::collection($this->features),
            'beds' => CategoryBedResource::collection($this->whenLoaded('beds')),
            'policies' => $this->localized_policies,
        ];
    }
}
