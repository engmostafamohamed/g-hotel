<?php

namespace App\Http\Repository\V1\CRM\Feature;

use App\Contracts\Features\FeatureRepositoryInterface;
use App\DataTransferObjects\FeatureDTOs\FeatureDTO;
use App\Models\Feature;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Utils\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Schedule;
class FeatureRepository implements FeatureRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator
    {
        $query = Feature::query();

        if ($obligatoryHotelId = current_hotel_id()) {
            $query->where('hotel_id', $obligatoryHotelId);
        } elseif (!empty($filters['hotel_id'])) {
            // Allow optional hotel_id filter when no obligatory one is set
            $query->where('hotel_id', $filters['hotel_id']);
        }

        $perPage = request()->query('per_page', 10);

        return $query->paginate((int) $perPage);
    }

    // public function getAllUnpaginated(array $filters): Collection
    // {
    //     $query = Feature::query();

    //     if (!empty($filters['hotel_id'])) {
    //         $query->where('hotel_id', $filters['hotel_id']);
    //     }

    //     return $query->get();
    // }

    public function create(FeatureDTO $dto): Feature
    {
        $feature = new Feature();
        $feature->setTranslations('name', $dto->name);
        $feature->hotel_id = $dto->hotel_id;

        if ($dto->logoPath) {
            $feature->logo = $dto->logoPath;
        }

        $feature->save();
        return $feature;
    }

    public function update(int $id, FeatureDTO $dto): Feature
    {
        $feature = $this->find($id);

        if (!empty($dto->name)) {
            $feature->setTranslations('name', $dto->name);
        }
        if ($dto->logoPath) {
            $feature->logo = $dto->logoPath;
        }
        if ($dto->hotel_id) {
            $feature->hotel_id = $dto->hotel_id;
        }
        if ($dto->logoPath) {
            $feature->logo = $dto->logoPath;
        }

        $feature->save();
        return $feature;
    }

    public function delete(int $id): void
    {
        $feature = $this->find($id);

        $feature->delete();
    }

    public function find(int $id): Feature
    {
        return Feature::query()
            ->when(current_hotel_id(), function ($q, $hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->findOrFail($id);
    }
}
