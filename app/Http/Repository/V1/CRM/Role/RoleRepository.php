<?php

namespace App\Http\Repository\V1\CRM\Role;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleRepository
{
    public function getAll():LengthAwarePaginator
    {
        $perPage = request()->query('per_page', 10);
        return Role::with('permissions')
            ->where('guard_name', 'employee')
            ->paginate((int) $perPage);
    }

    public function create(string $name, array $permissions)
    {
        $role = Role::create([
            'name' => $name,
            'guard_name' => 'employee',
        ]);

        if (!empty($permissions)) {
            $permissionModels = collect($permissions)->map(function ($permissionName) {
                return Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'employee',
                ]);
            });

            $role->syncPermissions($permissionModels);
        }

        return $role->load('permissions');
    }

    public function findById(int $id): Role
    {
        return Role::with('permissions')
            ->where('id', $id)
            ->where('guard_name', 'employee')
            ->firstOrFail();
    }


    public function update(Role $role, string $name, ?array $permissions = null): Role
    {
        $role->update(['name' => $name]);

        if ($permissions !== null) {
            $permissionModels = collect($permissions)->map(function ($permissionName) {
                return Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'employee',
                ]);
            });
            $role->syncPermissions($permissionModels);
        }

        return $role->load('permissions');
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }
}
