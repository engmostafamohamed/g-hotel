<?php

namespace App\Services\V1\CRM;

use App\DataTransferObjects\RoleDTOs\RoleDTO;
use App\Http\Repository\V1\CRM\Role\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    private RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllWithPermissions():LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function create(RoleDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            return $this->repository->create($dto->name, $dto->permissions ?? []);
        });
    }

    public function update(int $id, RoleDTO $dto)
    {
        return DB::transaction(function () use ($id, $dto) {
            $role = $this->repository->findById($id);

            return $this->repository->update(
                $role,
                $dto->name,
                $dto->permissions
            );
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $role = $this->repository->findById($id);

            $this->repository->delete($role);
        });
    }
}