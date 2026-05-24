<?php

namespace App\Http\Controllers\V1\CRM\Category;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\CRM\Category\StoreCategoryRequest;
use App\Http\Requests\V1\CRM\Category\UpdateCategoryRequest;
use App\Http\Requests\V1\CRM\Category\CategoryRequest;
use App\Http\Resources\V1\CRM\Category\PaginatedCategoryListResource;
use App\Http\Resources\V1\CRM\Category\CategoryResource;
use App\Http\Repository\V1\CRM\Category\CategoryRepository;
use App\DataTransferObjects\CategoryDTOs\CategoryDTO;
use App\Http\Resources\V1\CRM\Category\CategoryBedResource;
use App\Models\Bed;

class CategoryController extends Controller
{
    protected $Category;

    public function __construct(private CategoryRepository $category){}
    public function showAllCategories(Request $request)
    {
        try {
            $categories = $this->category->showCategoriesRepository($request);

            return ApiResponse::success(
                __('category.data_fetched_successfully'),
                new PaginatedCategoryListResource($categories),
                200
            );
        } catch (\Throwable $e) {
            return ApiResponse::error(
                __('category.unexpected'),
                [$e->getMessage()],
                500
            );
        }
    }
    public function showCategory(CategoryRequest  $request,$id)
    {
        $result= $this->category->showCategoryRepository($id,$request);
        if ($result['status'] === 'category_not_found') {
            return ApiResponse::error(__('category.category_id_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('category.data_fetched_successfully'),
            new CategoryResource($result['category']),
            200
        );
    }
    public function addCategory(StoreCategoryRequest  $request)
    {
        $result= $this->category->storeCategoryRepository(CategoryDTO::fromRequest($request));
        if ($result['status'] === 'image_not_found') {
            return ApiResponse::error(__('category.image_not_found'), [], 200);
        }

        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('category.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('category.data_added_successfully'),
            new CategoryResource($result['data']),
            201
        );
    }
    public function updateCategory(UpdateCategoryRequest $request, int $id)
    {
        $result= $this->category->updateCategoryRepository($id, CategoryDTO::fromRequest($request));
        if ($result['status'] === 'category_not_found') {
            return ApiResponse::error(__('category.category_id_not_found'), [], 200);
        }

        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('category.error_happend'), [], 500);
        }

        return ApiResponse::success(
            __('category.data_updated_successfully'),
            new CategoryResource($result['data']),
            201
        );
    }

    public function deleteCategory(Request $request,int $id)
    {
        $result= $this->category->deleteCategoryRepository($request,$id);
        if ($result['status'] === 'category_not_found') {
            return ApiResponse::error(__('category.category_id_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('category.data_deleted_successfully'),
            [],
            201
        );
    }

    public function listBeds()
    {
        try {
            $beds = $this->category->getAllBeds();
            return ApiResponse::success(
                __('category.beds_fetched_successfully'),
                CategoryBedResource::collection($beds),
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error(__('category.failed_to_fetch_beds'), $e->getMessage(), 500);
        }
    }
}
