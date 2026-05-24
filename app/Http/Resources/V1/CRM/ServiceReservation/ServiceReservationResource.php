<?php

namespace App\Http\Resources\V1\CRM\ServiceReservation;

use App\Http\Resources\V1\Api\Service\ServiceResource;
use App\Http\Resources\V1\CRM\Service\ServiceTimeSlotResource;
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
            'confirmed_by' => $this->confirmedBy ? [
                'employee_id' => $this->confirmedBy->id,
                'name' => $this->confirmedBy->name,
            ] : null,

            'cancelled_by' => $this->cancelledBy ? [
                'employee_id' => $this->cancelledBy->id,
                'name' => $this->cancelledBy->name,
            ] : null,
            'cancellation_reason' => $this->cancellation_reason,
            'created_at' => $this->created_at,
        ];
    }
}