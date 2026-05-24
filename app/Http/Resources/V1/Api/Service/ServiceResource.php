<?php

namespace App\Http\Resources\V1\Api\Service;

use App\Http\Resources\V1\Api\Service\ServiceCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', app()->getLocale()),
            'description' => $this->getTranslation('description', app()->getLocale()),
            'price' => $this->price,
            'category' => new ServiceCategoryResource($this->category),
            'image_path' => $this->image_path,
            'locations' => $this->locations,
            'is_schedulable' => $this->isSchedulable(),
            // 'schedule' => $this->schedules, //doesn't show schedules (check later)
            // 'time_slots' => ServiceTimeSlotResource::collection($this->timeSlots),
        ];
    }
}
