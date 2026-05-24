<?php

namespace App\Contracts\RoomType;

use App\DataTransferObjects\RoomTypeDTOs\RoomTypeDTO;
use App\Models\RoomType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RoomTypeRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator;

    // public function getAllUnpaginated(array $filters): Collection;

    public function create(RoomTypeDTO $dto): RoomType;

    public function update(RoomType $roomType, RoomTypeDTO $dto): RoomType;

    public function delete(RoomType $roomType): void;

    public function find(int $id): RoomType;
}
