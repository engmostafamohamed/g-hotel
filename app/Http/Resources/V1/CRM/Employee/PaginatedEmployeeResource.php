<?php

namespace App\Http\Resources\V1\CRM\Employee;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedEmployeeResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => collect($this->items())->map(function ($employee) use ($request) {
                return (new EmployeeResource($employee))->toArray($request);
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
