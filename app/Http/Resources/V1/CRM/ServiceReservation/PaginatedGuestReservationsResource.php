<?php

namespace App\Http\Resources\V1\CRM\ServiceReservation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedGuestReservationsResource extends ResourceCollection
{
    public function toArray($request): array
    {
        $grouped = $this->collection
            ->groupBy('guest_id')
            ->map(function ($reservations, $guestId) {
                $guest = $reservations->first()->guest;
                return [
                    'guest_id'   => $guest->id,
                    'guest_name' => $guest->first_name . ' ' . $guest->last_name,
                    'reservations' => $reservations->map(function ($reservation) {
                        return [
                            'reservation_id' => $reservation->id,
                            'service_name'   => $reservation->service->localized_name,
                            'price'          => $reservation->service->price,
                            'points_earned'  => $reservation->service->price * 10,
                            'date'           => optional($reservation->date)->toDateString(),
                        ];
                    })->values(),
                ];
            })->values();

        return [
            'data' => $grouped,
            'pagination' => [
                'total'        => $this->resource->total(),
                'per_page'     => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'last_page'    => $this->resource->lastPage(),
            ],
        ];
    }
}