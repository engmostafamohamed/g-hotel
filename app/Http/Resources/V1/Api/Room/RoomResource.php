<?php

namespace App\Http\Resources\V1\Api\Room;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name'           => $this->name,
            'description'    => $this->description,
            'base_price'    => $this->base_price,
            'final_price'    => $this->final_price ?? 0,
            'available_rooms'=> $this->available_rooms ?? 0,

            'seasonal_prices' => $this->seasonalPrices->map(function ($season) {
                return [
                    'id'              => $season->id,
                    'from'            => $season->from,
                    'to'              => $season->to,
                    'price'           => $season->price,
                    'points_discount' => $season->points_discount,
                ];
            }),

            'views' => $this->views->map(function ($view) {
                return [
                    'id'             => $view->id,
                    'type'           => $view->type,
                    'localized_type' => $view->localized_type ?? null,
                ];
            }),

            'category' => [
                'id'                   => $this->category->id ?? null,
                'name'                 => $this->category->name ?? null,
                'description'          => $this->category->description ?? null,
                'images'               => $this->category->images ?? [],
                'max_adults'           => $this->category->max_adults ?? null,
                'max_children'         => $this->category->max_children ?? null,
                'infants_allowed'      => $this->category->infants_allowed ?? null,
                'localized_name'       => $this->category->localized_name ?? null,
                'localized_description'=> $this->category->localized_description ?? null,
                'localized_policies'   => $this->category->localized_policies ?? null,

                'beds' => $this->category->beds->map(function ($bed) {
                    return [
                        'id'             => $bed->id,
                        'name'           => $bed->name,
                        'pivot_quantity' => $bed->pivot->quantity ?? null,
                    ];
                }),

                'features' => $this->category->features->map(function ($feature) {
                    return [
                        'id'   => $feature->id,
                        'name' => $feature->name,
                    ];
                }),
            ],
        ];
    }
}
