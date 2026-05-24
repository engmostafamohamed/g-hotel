<?php

namespace App\Contracts\Restaurants;

use App\DataTransferObjects\RestaurantDTOs\RestaurantDTO;
use App\Models\Restaurant;
use Illuminate\Pagination\LengthAwarePaginator;


interface RestaurantRepositoryInterface
{
    public function list(array $filters): LengthAwarePaginator;
    public function create(RestaurantDTO $dto): Restaurant;
    public function update(int $id, RestaurantDTO $dto): Restaurant;
    public function delete(int $id): void;
    public function find(int $id): Restaurant;
}
