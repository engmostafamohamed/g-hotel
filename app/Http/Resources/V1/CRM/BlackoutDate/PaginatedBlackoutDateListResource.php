<?php

namespace App\Http\Resources\V1\CRM\BlackoutDate;

use App\Models\BlackoutDate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginatedBlackoutDateListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'data'=>BlackoutDateResource::collection($this->collection),
            'meta'=>[
                'current_page'=>$this->currentPage(),
                'from'=>$this->firstItem(),
                'to'=>$this->lastItem(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
