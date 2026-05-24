<?php

namespace App\Http\Resources\V1\CRM\Employee;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedEmployeeListResource extends ResourceCollection
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
            'data' => collect($this->items())->map(function ($employee) {
                return (new EmployeeResource($employee))->toArray($employee);
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
