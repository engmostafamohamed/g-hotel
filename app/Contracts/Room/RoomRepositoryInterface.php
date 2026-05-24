<?php

namespace App\Contracts\Room;

use App\DataTransferObjects\RoomDTOs\BulkRoomDTO;
use App\DataTransferObjects\RoomDTOs\CRMRoomDTO;
use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RoomRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator;
    // public function getAllUnpaginated(array $filters): Collection;
    public function create(CRMRoomDTO $dto): Room;
    public function bulkCreate(BulkRoomDTO $dto): array;
    public function update(Room $room, CRMRoomDTO $dto): Room;
    public function delete(Room $room): void;
    public function find(int $id): Room;
}
