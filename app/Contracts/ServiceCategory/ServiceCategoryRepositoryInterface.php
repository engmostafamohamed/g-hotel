<?php

namespace App\Contracts\ServiceCategory;

use App\Model\ServiceCategory;

interface ServiceCategoryRepositoryInterface
{
    public function all();
    public function find($id): ?ServiceCategory;

    public function create(array $data);

    public function update(int $id, array $data);


    public function delete(int $category_id): void;

}
