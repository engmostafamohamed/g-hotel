<?php

namespace App\Contracts\V1\Api\HotelLocation;

use App\Models\HotelLocation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface HotelLocationRepositoryInterface
{
    public function index(): LengthAwarePaginator;
}