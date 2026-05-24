<?php

namespace App\Http\Resources\V1\CRM\SeasonalPrice;

use Illuminate\Http\Resources\Json\JsonResource;

class SeasonalPriceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'room_type_id' => $this->room_type_id,
            'from' => $this->from->toDateString(),
            'to' => $this->to->toDateString(),
            'price' => $this->price,
            'points_discount' => $this->points_discount,
        ];
    }
}
