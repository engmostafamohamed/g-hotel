<?php

namespace App\DataTransferObjects\RoleDTOs;

use App\Http\Requests\V1\CRM\Role\StoreRoleRequest;
use App\Http\Requests\V1\CRM\Role\UpdateRoleRequest;

class RoleDTO
{
    public string $name;
    public array $permissions;

    private function __construct(string $name, array $permissions)
    {
        $this->name = $name;
        $this->permissions = $permissions;
    }

    public static function fromRequest(StoreRoleRequest|UpdateRoleRequest $request): self
    {
        return new self(
            $request->input('name'),
            $request->input('permissions', [])
        );
    }
}
