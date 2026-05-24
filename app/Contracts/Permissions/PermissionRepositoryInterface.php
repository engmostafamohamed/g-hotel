<?php

namespace App\Contracts\Permissions;

use Spatie\Permission\Models\Permission;

interface PermissionRepositoryInterface
{
    public function getAll();

    public function create(string $name): Permission;

    public function findById(int $id): Permission;

    public function update(Permission $permission, string $name): Permission;

    public function delete(Permission $permission): void;

}
