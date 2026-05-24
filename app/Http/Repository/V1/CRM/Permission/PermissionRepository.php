<?php

namespace App\Http\Repository\V1\CRM\Permission;

use App\Contracts\Permissions\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function getAll():LengthAwarePaginator
    {
        $perPage = request()->query('per_page', 10);
        return Permission::where('guard_name', 'employee')->paginate((int) $perPage);
    }

    public function create(string $name): Permission
    {
        return Permission::create([
            'name' => $name,
            'guard_name' => 'employee',
        ]);
    }

    public function findById(int $id): Permission
    {
        return Permission::where('id', $id)
            ->where('guard_name', 'employee')
            ->firstOrFail();
    }


    public function update(Permission $permission, string $name): Permission
    {
        $permission->update([
            'name' => $name,
        ]);

        return $permission;
    }

    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}
