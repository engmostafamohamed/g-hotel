<?php

namespace App\Contracts\HotelLocation;

use App\Models\HotelLocation;

interface HotelLocationRepositoryInterface
{
    public function create(array $data): HotelLocation;

}
