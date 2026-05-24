<?php

namespace App\Services\V1\CRM;

use App\DataTransferObjects\RoomTypeDTOs\RoomTypeDTO;
use App\Http\Repository\V1\CRM\RoomType\RoomTypeRepository;
use Illuminate\Http\Request;

class RoomTypeService
{
    public function __construct(private RoomTypeRepository $repository) {}

    public function list(Request $request)
    {
        $filters = $request->only(['hotel_id', 'category_id', 'room_code', 'name']);
        return $this->repository->getAll($filters);
    }

    // public function listUnpaginated(Request $request)
    // {
    //     $filters = $request->only(['hotel_id', 'category_id', 'room_code', 'name']);
    //     return $this->repository->getAllUnpaginated($filters);
    // }

    public function create(RoomTypeDTO $dto)
    {
        return $this->repository->create($dto);
    }

    public function update(int $id, RoomTypeDTO $dto)
    {
        $roomType = $this->find($id);
        return $this->repository->update($roomType, $dto);
    }

    public function delete(int $id): void
    {
        $roomType = $this->find($id);
        $this->repository->delete($roomType);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }
}
