<?php

namespace App\Http\Resources\V1\Api\Feedback;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedFeedbackResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => collect($this->items())->map(function ($feedback) use ($request) {
                return (new FeedbackResource($feedback))->toArray($request);
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
