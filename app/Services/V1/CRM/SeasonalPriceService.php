<?php

namespace App\Services\V1\CRM;

use App\DataTransferObjects\SeasonalPriceDTOs\SeasonalPriceDTO;
use App\Http\Repository\V1\CRM\SeasonalPrice\SeasonalPriceRepository;
use App\Models\SeasonalPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SeasonalPriceService
{
    public function __construct(
        protected SeasonalPriceRepository $repository
    ) {}

    public function find(int $id): SeasonalPrice
    {
        return $this->repository->find($id);
    }

    public function getByRoomTypeId(int $roomTypeId, Request $request): Collection
    {
        $filters = $request->only(['from', 'to']);

        $filters['room_type_id'] = $roomTypeId;

        return $this->repository->getByRoomTypeId($filters);
    }

    public function create(SeasonalPriceDTO $dto): SeasonalPrice
    {
        return $this->repository->create($dto);
    }

    public function update(int $id, SeasonalPriceDTO $dto): SeasonalPrice
    {
        $seasonalPrice = $this->find($id);
        return $this->repository->update($seasonalPrice, $dto);
    }

    public function delete(int $id): void
    {
        $seasonalPrice = $this->find($id);
        $this->repository->delete($seasonalPrice);
    }

}
