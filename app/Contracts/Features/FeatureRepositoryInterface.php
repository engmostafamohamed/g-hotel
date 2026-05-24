<?php

namespace App\Contracts\Features;

use App\DataTransferObjects\FeatureDTOs\FeatureDTO;
use App\Models\Feature;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
interface FeatureRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator;
    // public function getAllUnpaginated(array $filters): Collection;
    public function create(FeatureDTO $dto): Feature;
    public function update(int $id, FeatureDTO $dto): Feature;
    public function delete(int $id): void;
    public function find(int $id): Feature;
    
    // public function showFeaturesRepository(Request $request);

    // public function storeFeatureRepository(Request $request);
}    