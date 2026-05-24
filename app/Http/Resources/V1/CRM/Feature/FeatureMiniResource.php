<?php

namespace App\Http\Resources\V1\CRM\Feature;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureMiniResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->localized_name
        ];
    }
}