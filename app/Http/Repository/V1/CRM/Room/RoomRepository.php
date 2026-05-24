<?php

namespace App\Http\Repository\V1\CRM\Room;

use App\DataTransferObjects\RoomDTOs\BulkRoomDTO;
use App\Models\Room;
use App\Contracts\Room\RoomRepositoryInterface;
use App\DataTransferObjects\RoomDTOs\CRMRoomDTO;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Http\Request;
use App\Utils\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Schedule;

class RoomRepository implements RoomRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator
    {
        $query = Room::with([
            'roomType',
            'roomType.views',
            'roomType.category',
            'roomType.category.beds',
            'roomType.category.features',
        ]);

        if ($obligatoryHotelId = current_hotel_id()) {
            $query->whereHas('roomType.category', function ($q) use ($obligatoryHotelId) {
                $q->where('hotel_id', $obligatoryHotelId);
            });
        } elseif (!empty($filters['hotel_id'])) {
            // Optional hotel_id filter (only if no obligatory hotel is set)
            $query->whereHas('roomType.category', function ($q) use ($filters) {
                $q->where('hotel_id', $filters['hotel_id']);
            });
        }

        if (!empty($filters['room_type_id'])) {
            $query->where('room_type_id', $filters['room_type_id']);
        }

        if (!empty($filters['room_number'])) {
            $query->where('room_number', 'like', '%' . $filters['room_number'] . '%');
        }

        $perPage = request()->query('per_page', 10);

        return $query->paginate((int) $perPage);
    }

    // public function getAllUnpaginated(array $filters): Collection
    // {
    //     // add hotel_id filter if provided. hotel_id is a part of the category where a room belongs to a type that belongs to a category
    //         $query = Room::with([
    //             'roomType',
    //             'roomType.views',
    //             'roomType.category',
    //         ]);

    //     if (!empty($filters['room_type_id'])) {
    //         $query->where('room_type_id', $filters['room_type_id']);
    //     }

    //     if (!empty($filters['room_number'])) {
    //         $query->where('room_number', 'like', '%' . $filters['room_number'] . '%');
    //     }

    //     if (!empty($filters['hotel_id'])) {
    //         $query->whereHas('roomType.category', function ($q) use ($filters) {
    //             $q->where('hotel_id', $filters['hotel_id']);
    //         });
    //     }

    //     return $query->get();
    // }

    public function create(CRMRoomDTO $dto): Room
    {
        $room = Room::create([
            'room_number' => $dto->room_number,
            'room_type_id' => $dto->room_type_id,
        ]);

        return $room->load(
            'roomType',
            'roomType.views',
            'roomType.category',
            'roomType.category.beds',
            'roomType.category.features',
        );
    }

    public function bulkCreate(BulkRoomDTO $dto): array
    {
        $rooms = [];

        foreach ($dto->room_numbers as $roomNumber) {
            $rooms[] = Room::create([
                'room_type_id' => $dto->room_type_id,
                'room_number' => $roomNumber
            ]);
        }

        return $rooms;
    }

    public function update(Room $room, CRMRoomDTO $dto): Room
    {
        $room->update(array_filter([
            'room_number' => $dto->room_number,
            'room_type_id' => $dto->room_type_id,
        ], fn($value) => !is_null($value)));

        return $room->load(
            'roomType',
            'roomType.views',
            'roomType.category',
            'roomType.category.beds',
            'roomType.category.features',
        );
    }


    public function delete(Room $room): void
    {
        $room->delete();
    }

    public function find(int $id): Room
    {
        return Room::with([
            'roomType',
            'roomType.views',
            'roomType.category',
            'roomType.category.beds',
            'roomType.category.features',
        ])
        ->where('id', $id)
        ->when(current_hotel_id(), function ($q, $hotelId) {
            $q->whereHas('roomType.category', function ($sub) use ($hotelId) {
                $sub->where('hotel_id', $hotelId);
            });
        })
        ->firstOrFail();
    }
}
