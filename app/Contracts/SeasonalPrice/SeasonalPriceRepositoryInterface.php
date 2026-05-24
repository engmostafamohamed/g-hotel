<?php

namespace App\Contracts\SeasonalPrice;

use App\DataTransferObjects\SeasonalPriceDTOs\SeasonalPriceDTO;
use App\Models\SeasonalPrice;
use Illuminate\Support\Collection;

interface SeasonalPriceRepositoryInterface
{
    public function find(int $id): SeasonalPrice;
    public function getByRoomTypeId(array $filters): Collection;
    public function create(SeasonalPriceDTO $dto): SeasonalPrice;
    public function update(SeasonalPrice $seasonalPrice, SeasonalPriceDTO $dto): SeasonalPrice;
    public function delete(SeasonalPrice $seasonalPrice);
}
