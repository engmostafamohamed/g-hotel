<?php

namespace App\Http\Repository\V1\Api\HotelLocation;

use App\Contracts\V1\Api\HotelLocation\HotelLocationRepositoryInterface;
use App\Models\HotelLocation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HotelLocationRepository implements HotelLocationRepositoryInterface
{
    public function index(): LengthAwarePaginator
    {
        return HotelLocation::with(['contactInfos', 'liveStyleImages'])
            ->where('is_active', true)
            ->paginate((int) request()->get('per_page', 10));
    }
}