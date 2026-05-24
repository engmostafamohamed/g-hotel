<?php

namespace App\Services\V1\CRM;

use App\DataTransferObjects\PermissionDTOs\PermissionDTO;
use App\Http\Repository\V1\CRM\Permission\PermissionRepository;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    private PermissionRepository $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        return $this->repository->getAll();
    }

    public function create(PermissionDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            return $this->repository->create($dto->name);
        });
    }

    public function update(int $id, PermissionDTO $dto)
    {
        return DB::transaction(function () use ($id, $dto) {
            $permission = $this->repository->findById($id);
            return $this->repository->update($permission, $dto->name);
        });
    }

    public function delete(int $id)
    {
        DB::transaction(function () use ($id) {
            $permission = $this->repository->findById($id);
            $this->repository->delete($permission);
        });
    }
}
