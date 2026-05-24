<?php

namespace App\Http\Repository\V1\CRM\Service;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\DataTransferObjects\RoleDTOs\RoleDTO;

interface ServiceRepositoryInterface
{
    public function showServicesRepository(Request $request);

    public function storeServiceRepository(Request $request);

    // public function update(int $id, RoleDTO $dto): Role;

    // public function delete(int $id): void;

    // public function exists(int $id): bool;

}
