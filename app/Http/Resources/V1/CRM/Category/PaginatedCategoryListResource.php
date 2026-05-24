<?php

namespace App\Http\Resources\V1\CRM\Category;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedCategoryListResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            // 'data' => collect($this->items())->map(fn($category) => [
            //     'id' => $category->id,
            //     'name' => $category->localized_name,
            //     'image_url' => $category->images,
            //     'hotel_id' => $category->hotel_id,
            //     'description' => $category->localized_description,
            //     'max_adults' => $category->max_adults,
            //     'max_children' => $category->max_children,
            //     'infants_allowed' => $category->infants_allowed,
            //     'features' => $category->features->map(fn($f) => [
            //         'id' => $f->id,
            //         'name' => $f->localized_name,
            //     ]),
            //     'beds' => $category->beds->map(fn($b) => [
            //         'id' => $b->id,
            //         'type' => $b->type,
            //         'quantity' => $b->pivot->quantity ?? null,
            //     ]),
            //     'policies' => $category->localized_policies,
            // ]),
            'data' => collect($this->items())->map(function ($category) use ($request) {
                return (new CategoryResource($category))->toArray($request);
            }),
            'pagination' => [
                'total' => $this->resource->total(),
                'per_page' => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
            ],
        ];
    }
}
