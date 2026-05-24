<?php

namespace App\Contracts\Service;

use App\Models\Service;
use Illuminate\Http\Request;

interface ServiceRepositoryInterface
{
    public function showServicesRepository(Request $request);
    public function storeServiceRepository(Request $request);
    public function getAllPaginated(int $perPage = 10);
    public function find(int $id): Service;
    public function update(int $id, array $data): Service;
    public function delete(int $id): void;
}
