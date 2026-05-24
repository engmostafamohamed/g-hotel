<?php

namespace App\Http\Resources\V1\CRM\ServiceReservation;

use Illuminate\Http\Resources\Json\ResourceCollection;


class PaginatedServiceReservationResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => collect($this->items())->map(function ($serviceReservation) use ($request) {
                return (new ServiceReservationResource($serviceReservation))->toArray($request);
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
