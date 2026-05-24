<?php
namespace App\Services\V1\CRM;

use App\Http\Repository\V1\CRM\ServiceCategory\ServiceCategoryRepository;
use App\Models\ServiceCategory;

class ServiceCategoryService
{
    public function __construct(protected ServiceCategoryRepository $repository)
    {
    }

    public function getAll($perPage)
    {
        return $this->repository->all($perPage);
    }

    public function getOne($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $category_id)
    {
        return $this->repository->delete($category_id);
    }
}