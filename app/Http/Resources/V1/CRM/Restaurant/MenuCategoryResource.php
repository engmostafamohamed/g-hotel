<?php

namespace App\Http\Resources\V1\CRM\Restaurant;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            // 'name' => $this->getTranslations('name'),
            'name' => $this->name,
            'menu_items' => MenuItemResource::collection($this->menuItems),
        ];
    }
}
