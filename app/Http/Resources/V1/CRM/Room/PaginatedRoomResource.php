<?php

namespace App\Http\Resources\V1\CRM\Room;

use App\Http\Resources\V1\CRM\Room\RoomResourceWithTypeAndCategory;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedRoomResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => collect($this->items())->map(function ($room) use ($request) {
                // return (new RoomResourceWithTypeAndCategory($room))->toArray($request);
                return (new RoomResource($room))->toArray($request);
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
