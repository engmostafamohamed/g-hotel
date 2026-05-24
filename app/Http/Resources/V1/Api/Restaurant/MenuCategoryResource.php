<?php

namespace App\Http\Resources\V1\Api\Restaurant;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->name, // or $this->getTranslation('name', app()->getLocale())
            'items' => $this->menuItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => (float) $item->price,
                    'dietary_tags' => $item->dietary_tags,
                ];
            }),
            // 'name' => $this['name'], 
            // 'items' => collect($this['items'])->map(function ($item) {
            //     return [
            //         'id' => $item['id'],
            //         'name' => $item['name'],
            //         'description' => $item['description'],
            //         'price' => $item['price'],
            //         'dietary_tags' => $item['dietary_tags'],
            //     ];
            // }),
        ];
    }
}
