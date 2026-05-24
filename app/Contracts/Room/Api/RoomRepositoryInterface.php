<?php

namespace App\Contracts\Room\Api;
use Illuminate\Http\Request;
use App\DataTransferObjects\RoomDTOs\Api\BookRoomDTO;
use App\DataTransferObjects\RoomDTOs\Api\RoomDTO;
// use App\DataTransferObjects\RoomDTOs\RoomDTO;
use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RoomRepositoryInterface
{
    public function findRooms(RoomDTO  $filters);
    public function bookRoom(BookRoomDTO $dto);
}
