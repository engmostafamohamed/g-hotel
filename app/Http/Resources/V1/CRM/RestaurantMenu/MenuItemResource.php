<?php

namespace App\Http\Resources\V1\CRM\RestaurantMenu;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->getLocalizedNameAttribute(),
            'description' => $this->getLocalizedDescriptionAttribute(),
            'price' => $this->price,
            'image_path'=>$this->image_path,
            'dietary_tags' => $this->dietary_tags,
            'category_id' => $this->menu_category_id,
        ];
    }
}
