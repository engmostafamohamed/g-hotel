<?php

namespace App\Contracts\Categories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\DataTransferObjects\CategoryDTOs\CategoryDTO;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
interface CategoryRepositoryInterface
{
    public function showCategoriesRepository(Request $dto): LengthAwarePaginator;

    public function storeCategoryRepository(CategoryDTO $request);

   public function updateCategoryRepository(int $id ,CategoryDTO $request);

    public function deleteCategoryRepository(Request $request,int $id);

    // public function exists(int $id): bool;

}
