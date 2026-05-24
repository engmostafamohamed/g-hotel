<?php

namespace App\Http\Controllers\V1\CRM\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Role\StoreRoleRequest;
use App\Http\Requests\V1\CRM\Role\UpdateRoleRequest;
use App\DataTransferObjects\RoleDTOs\RoleDTO;
use App\Http\Resources\V1\CRM\Role\RoleResource;
use App\Services\V1\CRM\RoleService;
use App\Helpers\ApiResponse;
use App\Http\Resources\V1\CRM\Role\PaginatedRoleResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class RoleController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        try {
            $roles = $this->roleService->getAllWithPermissions();

            return ApiResponse::success(
                __('role.fetched'),
                new PaginatedRoleResource($roles),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('role.unexpected'),
                [$e->getMessage()],
                500
            );
        }
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            $dto = RoleDTO::fromRequest($request);
            $role = $this->roleService->create($dto);

            return ApiResponse::success(
                __('role.created'),
                new RoleResource($role),
                201
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('role.unexpected_create'),
                [$e->getMessage()],
                500
            );
        }
    }

    public function update(UpdateRoleRequest $request, int $id)
    {
        try {
            $dto = RoleDTO::fromRequest($request);
            $role = $this->roleService->update($id, $dto);

            return ApiResponse::success(
                __('role.updated'),
                new RoleResource($role),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                __('role.not_found'),
                [],
                404
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('role.unexpected_update'),
                [$e->getMessage()],
                500
            );
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->roleService->delete($id);

            return ApiResponse::success(
                __('role.deleted'),
                [],
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                __('role.not_found'),
                [],
                404
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('role.unexpected_delete'),
                [$e->getMessage()],
                500
            );
        }
    }
}