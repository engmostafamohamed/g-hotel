<?php

namespace App\Http\Resources\V1\CRM\Guest;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedGuestListResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => collect($this->items())->map(function ($guest) {
                return [
                    'id' => $guest->id,
                    'name' => trim($guest->first_name . ' ' . $guest->last_name),
                ];
            }),
            'pagination' => [
                'total' => $this->resource->total(),
                'per_page' => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
            ]
        ];
    }
}