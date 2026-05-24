<?php

namespace App\Http\Controllers\V1\CRM\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Permission\StorePermissionRequest;
use App\Http\Requests\V1\CRM\Permission\UpdatePermissionRequest;
use App\DataTransferObjects\PermissionDTOs\PermissionDTO;
use App\Http\Resources\V1\CRM\Role\PermissionResource;
use App\Services\V1\CRM\PermissionService;
use App\Helpers\ApiResponse;
use App\Http\Resources\V1\CRM\Role\PaginatedPermissionResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class PermissionController extends Controller
{
    private PermissionService $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $permissions = $this->service->list();

            return ApiResponse::success(
                __('permission.fetched'),
                new PaginatedPermissionResource($permissions),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('permission.unexpected'),
                [$e->getMessage()],
                500
            );
        }
    }

    public function store(StorePermissionRequest $request)
    {
        try {
            $dto = PermissionDTO::fromRequest($request);
            $permission = $this->service->create($dto);

            return ApiResponse::success(
                __('permission.created'),
                new PermissionResource($permission),
                201
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('permission.unexpected_create'),
                [$e->getMessage()],
                500
            );
        }
    }

    public function update(UpdatePermissionRequest $request, int $id)
    {
        try {
            $dto = PermissionDTO::fromRequest($request);
            $permission = $this->service->update($id, $dto);

            return ApiResponse::success(
                __('permission.updated'),
                new PermissionResource($permission),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                __('permission.not_found'),
                [],
                404
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('permission.unexpected_update'),
                [$e->getMessage()],
                500
            );
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->service->delete($id);

            return ApiResponse::success(
                __('permission.deleted'),
                [],
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                __('permission.not_found'),
                [],
                404
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('permission.unexpected_delete'),
                [$e->getMessage()],
                500
            );
        }
    }
}