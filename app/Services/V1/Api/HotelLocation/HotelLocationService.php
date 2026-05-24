<?php

namespace App\Services\V1\Api\HotelLocation;

use App\Contracts\V1\Api\HotelLocation\HotelLocationRepositoryInterface;
use App\Http\Repository\V1\Api\HotelLocation\HotelLocationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HotelLocationService
{
    public function __construct(
        protected HotelLocationRepository $repository
    ) {}

    public function index(): LengthAwarePaginator
    {
        return $this->repository->index();
    }
}