<?php

namespace App\Http\Resources\V1\CRM\Role;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedPermissionResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => collect($this->items())->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'guard_name' => $permission->guard_name
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