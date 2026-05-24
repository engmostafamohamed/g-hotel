<?php

namespace App\Http\Repository\V1\CRM\HotelLocation;

use App\Contracts\HotelLocation\HotelLocationRepositoryInterface;
use App\Models\HotelLocation;
use App\Traits\UsesHotelScope;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HotelLocationRepository implements HotelLocationRepositoryInterface
{
    use UsesHotelScope;
    public function create(array $data): HotelLocation
    {
        if ($this->getHotelIdFromAuth()) {
            throw new AuthorizationException('Only super admins can create hotel locations.');
        }
        // force is_active to false if not fully configured (basic logic)
        if (!isset($data['is_active']) || $data['is_active'] !== true) {
            $data['is_active'] = false;
        }

        return HotelLocation::create($data);
    }

    public function getAllPaginated(int $perPage = 10)
    {
        if ($this->getHotelIdFromAuth()) {
            throw new AuthorizationException('Only super admins can view all hotel locations.');
        }
        return HotelLocation::with(['services', 'restaurants', 'employees'])->paginate($perPage);
    }

    public function find(int $id)
    {
        // return HotelLocation::with(['services', 'restaurants', 'employees'])->findOrFail($id);
        $hotel = HotelLocation::with(['services', 'restaurants', 'employees'])->find($id);

        if (!$hotel) {
            throw new ModelNotFoundException("Hotel not found.");
        }

        $hotelId = $this->getHotelIdFromAuth();
        if ($hotelId && $hotel->id !== $hotelId) {
            throw new AuthorizationException('Unauthorized access to this hotel.');
        }

        return $hotel;
    }

    public function update(array $data, int $id)
    {
        $hotel = HotelLocation::findOrFail($id);
        $hotelId = $this->getHotelIdFromAuth();
        if ($hotelId && $hotel->id !== $hotelId) {
            throw new AuthorizationException('Unauthorized to update this hotel.');
        }
        $hotel->update($data);
        return $hotel->refresh();
    }

    public function delete(int $id)
    {
        if ($this->getHotelIdFromAuth()) {
            throw new AuthorizationException('Only super admins can delete hotel locations.');
        }
        $hotel = HotelLocation::findOrFail($id);
        $hotel->delete();
    }

}
