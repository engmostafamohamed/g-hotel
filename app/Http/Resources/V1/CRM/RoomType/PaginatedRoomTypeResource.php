<?php

namespace App\Http\Resources\V1\CRM\RoomType;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedRoomTypeResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => collect($this->items())->map(function ($roomType) use ($request) {
                return (new RoomTypeResource($roomType))->toArray($request);
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
