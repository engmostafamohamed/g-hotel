<?php

namespace App\Http\Resources\V1\CRM\Feature;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedFeatureListResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => collect($this->items())->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->localized_name,
                    // 'hotel_id' => $feature->hotel_id, //category already has hotel_id
                    'logo' => $feature->logo
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
