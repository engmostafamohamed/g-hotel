<?php

namespace App\Http\Repository\V1\CRM\RoomType;

use App\Contracts\RoomType\RoomTypeRepositoryInterface;
use App\DataTransferObjects\RoomTypeDTOs\RoomTypeDTO;
use App\Models\RoomType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RoomTypeRepository implements RoomTypeRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator
    {
        $today = now()->toDateString();
        
        $query = RoomType::with([
            'views',
            'category',
            'seasonalPrices' => function ($q) use ($today) {
                $q->whereDate('from', '<=', $today)
                ->whereDate('to', '>=', $today)
                ->orderBy('from', 'desc');
            }
        ]);

        if ($obligatoryHotelId = current_hotel_id()) {
            // If both obligatory and filter hotel_id exist and are different → unauthorized
            if (!empty($filters['hotel_id']) && (int) $filters['hotel_id'] !== (int) $obligatoryHotelId) {
                throw new AuthorizationException();
            }

            // Always apply obligatory hotel filter
            $query->whereHas('category', function ($q) use ($obligatoryHotelId) {
                $q->where('hotel_id', $obligatoryHotelId);
            });
        } elseif (!empty($filters['hotel_id'])) {
            // Apply optional hotel filter only when no obligatory one is set
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('hotel_id', $filters['hotel_id']);
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['name'])) {
            $query->where('name->en', 'like', '%' . $filters['name'] . '%')
                  ->orWhere('name->ar', 'like', '%' . $filters['name'] . '%');
        }

        $perPage = request()->query('per_page', 10);
        return $query->paginate((int) $perPage);
    }

    // public function getAllUnpaginated(array $filters): Collection
    // {
    //     $query = RoomType::with('views', 'seasonalPrices');

    //     if (!empty($filters['category_id'])) {
    //         $query->where('category_id', $filters['category_id']);
    //     }

    //     if (!empty($filters['name'])) {
    //         $query->where('name->en', 'like', '%' . $filters['name'] . '%')
    //               ->orWhere('name->ar', 'like', '%' . $filters['name'] . '%');
    //     }

    //     if (!empty($filters['hotel_id'])) {
    //         $query->whereHas('category', function ($q) use ($filters) {
    //             $q->where('hotel_id', $filters['hotel_id']);
    //         });
    //     }

    //     return $query->get();
    // }

    public function create(RoomTypeDTO $dto): RoomType
    {
        $roomType = new RoomType();
        $roomType->room_code = $dto->room_code;
        $roomType->setTranslations('name', $dto->name);
        $roomType->setTranslations('description', $dto->description);
        $roomType->base_price = $dto->base_price;
        $roomType->category_id = $dto->category_id;
        $roomType->save();

        if (!empty($dto->views)) {
            $roomType->views()->sync($dto->views);
        }

        $roomType->load('views', 'category');

        return $roomType;
    }

    public function update(RoomType $roomType, RoomTypeDTO $dto): RoomType
    {
        if (!empty($dto->room_code)) {
            $roomType->room_code = $dto->room_code;
        }

        if (!empty($dto->name)) {
            $roomType->setTranslations('name', $dto->name);
        }

        if (!empty($dto->description)) {
            $roomType->setTranslations('description', $dto->description);
        }

        if (!is_null($dto->base_price)) {
            $roomType->base_price = $dto->base_price;
        }

        if (!empty($dto->category_id)) {
            $roomType->category_id = $dto->category_id;
        }

        
        if (!is_null($dto->views)) {
            $roomType->views()->sync($dto->views);
        }
        
        $roomType->save();

        $roomType->load('views', 'category');

        return $roomType;
    }

    public function delete(RoomType $roomType): void
    {
        $roomType->delete();
    }

    public function find(int $id): RoomType
    {
        $today = now()->toDateString();
        return RoomType::with([
            'views',
            'category',
            'seasonalPrices' => function ($q) use ($today) {
                $q->whereDate('from', '<=', $today)
                ->whereDate('to', '>=', $today)
                ->orderBy('from', 'desc');
            }
        ])
        ->where('id', $id)
        ->when(current_hotel_id(), function ($q, $hotelId) {
            $q->whereHas('category', function ($sub) use ($hotelId) {
                $sub->where('hotel_id', $hotelId);
            });
        })
        ->firstOrFail();
    }
}
