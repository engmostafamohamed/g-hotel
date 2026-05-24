<?php

namespace App\Http\Resources\V1\Api\Service;

use Illuminate\Http\Resources\Json\ResourceCollection;


class PaginatedServiceTimeSlotResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => collect($this->items())->map(function ($service_time_slot) use ($request) {
                return (new ServiceTimeSlotResource($service_time_slot))->toArray($request);
            }),
            'pagination' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
