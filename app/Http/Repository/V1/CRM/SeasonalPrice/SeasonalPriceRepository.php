<?php

namespace App\Http\Repository\V1\CRM\SeasonalPrice;

use App\Contracts\SeasonalPrice\SeasonalPriceRepositoryInterface;
use App\DataTransferObjects\SeasonalPriceDTOs\SeasonalPriceDTO;
use App\Models\SeasonalPrice;
use Illuminate\Support\Collection;

class SeasonalPriceRepository implements SeasonalPriceRepositoryInterface
{

    public function find(int $id): SeasonalPrice
    {
        return SeasonalPrice::findOrFail($id);
    }

    public function getByRoomTypeId(array $filters): Collection
    {
        $query = SeasonalPrice::query();

        if (!empty($filters['room_type_id'])) {
            $query->where('room_type_id', $filters['room_type_id']);
        }

        if (!empty($filters['from'])) {
            $query->where('to', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->where('from', '<=', $filters['to']);
        }

        return $query->get();
    }

    public function create(SeasonalPriceDTO $dto): SeasonalPrice
    {
        return SeasonalPrice::create([
            'room_type_id' => $dto->room_type_id,
            'from' => $dto->from,
            'to' => $dto->to,
            'price' => $dto->price,
            'points_discount' => $dto->points_discount,
        ]);
    }

    public function update(SeasonalPrice $seasonalPrice, SeasonalPriceDTO $dto): SeasonalPrice
    {
        $data = [];

        if (!is_null($dto->room_type_id)) {
            $data['room_type_id'] = $dto->room_type_id;
        }

        if (!is_null($dto->from)) {
            $data['from'] = $dto->from;
        }

        if (!is_null($dto->to)) {
            $data['to'] = $dto->to;
        }

        if (!is_null($dto->price)) {
            $data['price'] = $dto->price;
        }

        if (!is_null($dto->points_discount)) {
            $data['points_discount'] = $dto->points_discount;
        }

        $seasonalPrice->update($data);

        return $seasonalPrice;
    }

    public function delete(SeasonalPrice $roomType): void
    {
        $roomType->delete();
    }

}
