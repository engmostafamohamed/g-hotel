<?php

namespace App\Http\Resources\V1\CRM\Room;

use App\Http\Resources\V1\CRM\RoomType\RoomTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResourceWithTypeAndCategory extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'room_number' => $this->room_number,
            // 'room_type_id' => $this->room_type_id,
            'room_type' => new RoomTypeResource($this->whenLoaded('roomType')),
        ];
    }
}
