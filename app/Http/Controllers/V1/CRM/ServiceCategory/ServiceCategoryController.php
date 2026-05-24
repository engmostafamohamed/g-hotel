<?php

namespace App\Http\Controllers\V1\CRM\ServiceCategory;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\ServiceCategory\StoreServiceCategoryRequest;
use App\Http\Requests\V1\CRM\ServiceCategory\UpdateeServiceCategoryRequest;
use App\Http\Resources\V1\CRM\ServiceCategory\ServiceCategoryResource;
use App\Models\ServiceCategory;
use App\Services\V1\CRM\ServiceCategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function __construct(protected ServiceCategoryService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $categories = $this->service->getAll($request->input('per_page', 10));
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Service Categories fetched successfully.',
                'data' => ServiceCategoryResource::collection($categories),
                'meta' => [
                    'current_page' => $categories->currentPage(),
                    'total' => $categories->total(),
                    'per_page' => $categories->perPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Service Category could not be created.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function store(StoreServiceCategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->service->create($request->validated());
            return response()->json([
                'success' => true,
                'statusCode' => 201,
                'message' => 'Service Category created successfully.',
                'data' => new ServiceCategoryResource($category)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Service Category could not be created.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->service->getOne($id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Service Category fetched successfully.',
                'data' => new ServiceCategoryResource($category)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Service Category not found.'
            ], 404);
        }
    }

    public function update(UpdateeServiceCategoryRequest $request, int $id): JsonResponse
    {
        try {
            $updated_category = $this->service->update($id, $request->validated());
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => "Service Category updated successfully.",
                'data' => new ServiceCategoryResource($updated_category)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Service Category could not be updated.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Service category deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Service Category not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Service Category could not be deleted.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
}
