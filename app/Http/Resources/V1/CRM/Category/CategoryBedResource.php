<?php

namespace App\Http\Resources\V1\CRM\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryBedResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'quantity' => $this->pivot->quantity ?? 0,
        ];
    }
}
