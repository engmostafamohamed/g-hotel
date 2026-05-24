<?php

namespace App\Http\Resources\V1\CRM\Role;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedRoleResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => collect($this->items())->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'permissions' => PermissionResource::collection($role->permissions)
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