<?php

namespace App\Http\Repository\V1\Api\Service;

use App\Models\Service;
use App\Models\ServiceReservation;
use Illuminate\Http\Request;

class ServiceRepository
{
    public function filter(Request $request)
    {
        $hotelId = $request->header('hotel-id') ?? 1;
        if (!$hotelId) {
            throw new \Exception('hotel_id header is required.');
        }

        $filters = $request->only([
            'name',
            'category_id',
            'min_price',
            'max_price'
        ]);

        $query = Service::query()->where('hotel_id', $request->header('hotel-id'));

        if (!empty($filters['name'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name->en', 'like', '%' . $filters['name'] . '%')
                    ->orWhere('name->ar', 'like', '%' . $filters['name'] . '%');
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query->paginate(10);
    }
}
