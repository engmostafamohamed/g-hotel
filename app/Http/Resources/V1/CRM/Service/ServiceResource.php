<?php

namespace App\Http\Resources\V1\CRM\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'localized_name' => $this->localized_name,
            'localized_description' => $this->localized_description,
            'price' => $this->price,
            'image_path' => $this->image_path,
            'version' => $this->version,
            'sync_with_pms' => $this->sync_with_pms,
            'pms_sync_status' => $this->pms_sync_status,
            'category' => $this->category->name ?? null,
            'hotel_location' => $this->hotelLocation->property_code ?? null,
            'locations' => $this->locations ?? [],
            'time_slots' => $this->timeSlots->map(function ($slot) {
                return [
                    'start' => $slot->start,
                    'end' => $slot->end,
                    'max_capacity' => $slot->max_capacity,
                ];
            }),
            'schedules' => $this->schedules->map(function ($schedule) {
                return [
                    'day' => $schedule->day_of_week,
                    'opening_time' => $schedule->work_from,
                    'closing_time' => $schedule->work_to,
                ];
            }),
        ];
    }
}
