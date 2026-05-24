<?php

namespace App\Http\Resources\V1\CRM\Survey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
class PaginatedSurveyResource extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => SurveyResource::collection($this->collection),
            'pagination' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ],
        ];
    }
}
