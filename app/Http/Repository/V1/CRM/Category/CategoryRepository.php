<?php

namespace App\Http\Repository\V1\CRM\Category;

use App\Contracts\Categories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Utils\FileUpload;
use Illuminate\Database\QueryException;
use App\DataTransferObjects\CategoryDTOs\CategoryDTO;
use App\Models\Bed;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function showCategoriesRepository(Request $request): LengthAwarePaginator
    {
        $filters = $request->only(['hotel_id']);
        $query = Category::query()
            ->with(['features', 'beds']);

        if ($obligatoryHotelId = current_hotel_id()) {
            $query->where('hotel_id', $obligatoryHotelId);
        } elseif (!empty($filters['hotel_id'])) {
            $query->where('hotel_id', $filters['hotel_id']);
        }

        $perPage = request()->query('per_page', 10);
        return $query->paginate((int) $perPage);
    }
    public function showCategoryRepository(int $id, Request $request)
    {
        $query = Category::with(['features', 'beds'])
            ->where('id', $id)
            ->first();

        return $query
            ? ['status' => 'success', 'category' => $query]
            : ['status' => 'category_not_found'];
    }

    public function storeCategoryRepository(CategoryDTO $dto)
    {
        try {
            if (empty($dto->category_images)) {
                return ['status' => 'image_not_found'];
            }

            $imagePath = [];
            foreach ($dto->category_images as $file) {
                $imagePath[] = FileUpload::uploadImageOnLocal($file, 'categoryImages');
            }

            $record = Category::create([
                'name' => $dto->category_name,
                'images' => $imagePath,
                'description' => $dto->category_description,
                'hotel_id' => $dto->hotel_id,
                'max_adults' => $dto->max_adults,
                'max_children' => $dto->max_children,
                'infants_allowed' => $dto->infants_allowed,
                'policies' => $dto->policies,
            ]);

            if (!empty($dto->feature_ids)) {
                $record->features()->sync($dto->feature_ids);
            }

            if (!empty($dto->bed_data)) {
                $syncData = collect($dto->bed_data)->mapWithKeys(fn($bed) => [
                    $bed['bed_id'] => ['quantity' => $bed['quantity'] ?? 1]
                ])->toArray();

                $record->beds()->sync($syncData);
            }

            $record->load(['features', 'beds']);

            return ['status' => 'success', 'data' => $record];

        } catch (QueryException $e) {
            return ['status' => 'db_error', 'message' => $e->getMessage()];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function updateCategoryRepository(int $id, CategoryDTO $dto)
    {
        try {
            $record = Category::where('id', $id)->whereNull('deleted_at')->first();
            if (!$record) {
                return ['status' => 'category_not_found'];
            }

            $updateData = [];

            if (!empty($dto->category_name)) $updateData['name'] = $dto->category_name;
            if (!empty($dto->category_description)) $updateData['description'] = $dto->category_description;
            if (!empty($dto->category_images)) {
                $updateData['images'] = array_map(
                    fn($file) => FileUpload::uploadImageOnLocal($file, 'categoryImages'),
                    $dto->category_images
                );
            }
            if ($dto->max_adults !== null) $updateData['max_adults'] = $dto->max_adults;
            if ($dto->max_children !== null) $updateData['max_children'] = $dto->max_children;
            if ($dto->infants_allowed !== null) $updateData['infants_allowed'] = $dto->infants_allowed;
            if (!empty($dto->policies)) $updateData['policies'] = $dto->policies;

            $record->update($updateData);

            if (!empty($dto->feature_ids)) {
                $record->features()->sync($dto->feature_ids);
            }

            if (!empty($dto->bed_data)) {
                $syncData = collect($dto->bed_data)->mapWithKeys(fn($bed) => [
                    $bed['bed_id'] => ['quantity' => $bed['quantity'] ?? 1]
                ])->toArray();

                $record->beds()->sync($syncData);
            }

            $record->load(['features', 'beds']);

            return ['status' => 'success', 'data' => $record];

        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function deleteCategoryRepository(Request $request, int $id)
    {
        try {
            $record = Category::where('id', $id)->whereNull('deleted_at')->first();
            if (!$record) {
                return ['status' => 'category_not_found'];
            }

            $record->delete();
            return ['status' => 'success'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getAllBeds()
    {
        return Bed::all();
    }
}