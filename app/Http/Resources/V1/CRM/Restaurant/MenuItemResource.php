<?php

namespace App\Http\Resources\V1\CRM\Restaurant;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            // 'name' => $this->getTranslations('name'),
            // 'description' => $this->getTranslations('description'),
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'dietary_tags' => $this->dietary_tags,
        ];
    }
}
