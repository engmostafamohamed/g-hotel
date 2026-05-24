<?php

namespace App\Services\V1\CRM;

use App\DataTransferObjects\RoomDTOs\BulkRoomDTO;
use App\DataTransferObjects\RoomDTOs\CRMRoomDTO;
use App\Http\Repository\V1\CRM\Room\RoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomService
{
    public function __construct(private RoomRepository $repository) {}

    public function list(Request $request)
    {
        // extract logged in users hotel id and add obligatory filter if not null
        $filters = $request->only(['hotel_id', 'room_type_id', 'room_number']);
        //add availability to filters later
        return $this->repository->getAll($filters);
    }

    // public function listUnpaginated(Request $request)
    // {
    //     $filters = $request->only(['hotel_id', 'room_type_id', 'room_number']);
    //     //add availability to filters later
    //     return $this->repository->getAllUnpaginated(filters: $filters);
    // }

    public function create(CRMRoomDTO $dto)
    {
        return $this->repository->create($dto);
    }

    public function bulkCreate(BulkRoomDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            return $this->repository->bulkCreate($dto);
        });
    }

    public function update(int $id, CRMRoomDTO $dto)
    {
        $room = $this->find($id);
        return $this->repository->update($room, $dto);
    }

    public function delete(int $id): void
    {
        $room = $this->find($id);
        $this->repository->delete($room);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }
}
