<?php

namespace App\Http\Repository\V1\CRM\ServiceCategory;

use App\Models\ServiceCategory;

class ServiceCategoryRepository
{
    public function all($perPage)
    {
        return ServiceCategory::latest()->paginate($perPage);
    }

    public function find($id): ?ServiceCategory
    {
        return ServiceCategory::findOrFail($id);
    }

    public function create(array $data)
    {
        return ServiceCategory::create($data);
    }

    public function update(int $id, array $data)
    {
        $category = ServiceCategory::findOrFail($id);
        if (isset($data['name'])) {
            $category->name = $data['name'];
        }

        if (isset($data['description'])) {
            $category->description = $data['description'];
        }
        if (isset($data['type'])) {
            $category->type = $data['type'];
        }

        $category->save();
        return $category->fresh();
    }

    public function delete(int $category_id): void
    {
        $category = ServiceCategory::findOrFail($category_id);
        $category->delete();
    }
}
