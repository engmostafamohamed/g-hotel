<?php

namespace App\Http\Resources\V1\CRM\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMiniResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->localized_name,
        ];
    }
}