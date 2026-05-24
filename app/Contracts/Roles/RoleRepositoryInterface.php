<?php

namespace App\Contracts\Roles;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\DataTransferObjects\RoleDTOs\RoleDTO;

interface RoleRepositoryInterface
{
    public function getAll();

    public function create(RoleDTO $dto): Role;

    public function update(int $id, RoleDTO $dto): Role;

    public function delete(int $id): void;

    public function exists(int $id): bool;

}
