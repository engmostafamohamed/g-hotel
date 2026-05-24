<?php

namespace App\Http\Resources\V1\CRM\BlackoutDate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\CRM\Category\CategoryResource;
class BlackoutDateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'blackoutDate_id' => $this->id,
            'name' => $this->getTranslations('name'),
            'blackoutDate_start_date' => $this->start_date,
            'blackoutDate_end_date' => $this->end_date,
            'allow_existing_booking' => $this->allow_existing_booking,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
