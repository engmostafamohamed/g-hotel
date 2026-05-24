<?php

namespace App\Http\Resources\V1\Api\ServiceReservation;

use App\Http\Resources\V1\Api\Service\ServiceResource;
use App\Http\Resources\V1\Api\Service\ServiceTimeSlotResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceReservationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'service' => new ServiceResource($this->service),
            'date' => $this->date->toDateString(),
            'time_slot_id' => $this->service_time_slot_id,
            'from' => optional($this->timeSlot)->start,
            'to' => optional($this->timeSlot)->end,
            'status' => $this->status,
            'notes' => $this->notes,
            'cancellation_reason' => $this->cancellation_reason,
            'created_at' => $this->created_at,
        ];
    }
}