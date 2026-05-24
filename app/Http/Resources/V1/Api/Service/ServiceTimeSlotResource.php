<?php

namespace App\Http\Resources\V1\Api\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTimeSlotResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'start' => $this->start,
            'end' => $this->end,
            'max_capacity' => $this->max_capacity
        ];
    }
}
