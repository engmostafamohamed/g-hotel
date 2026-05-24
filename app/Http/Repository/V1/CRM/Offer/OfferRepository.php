<?php

namespace App\Http\Repository\V1\CRM\Offer;

use App\Models\Offer;
use App\Models\Service;
use App\Traits\UsesHotelScope;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class OfferRepository
{
    use UsesHotelScope;
    public function store(array $data)
    {
        // $hotelId = $this->getHotelIdFromAuth();

        $service = Service::where('id', $data['service_id'])
            // ->where('hotel_id', $hotelId)
            ->first();
        if (!$service) {
            throw new ModelNotFoundException('Service not found for this hotel.');
        }

        $offer = Offer::create([
            'type' => $data['type'],
            'value' => $data['value'],
            'total_inventory' => $data['inventory']['total'],
            'per_guest_inventory' => $data['inventory']['per_guest'],
            'start_date' => $data['valid_dates'][0],
            'end_date' => $data['valid_dates'][1],
            'service_id' => $data['service_id'],
            'redemption_code' => strtoupper(Str::random(8)),
        ]);

        return $offer->load('service');
    }
    public function update(array $data, int $id): Offer
    {
        // $hotelId = $this->getHotelIdFromAuth();

        $offer = Offer::where('id', $id)
            // ->whereHas('service', fn($q) => $q->where('hotel_id', $hotelId))
            ->whereHas('service')
            ->firstOrFail();


        if (isset($data['type'])) {
            $offer->type = $data['type'];
        }

        if (isset($data['value'])) {
            $offer->value = $data['value'];
        }

        if (isset($data['inventory']['total'])) {
            $offer->total_inventory = $data['inventory']['total'];
        }

        if (isset($data['inventory']['per_guest'])) {
            $offer->per_guest_inventory = $data['inventory']['per_guest'];
        }

        if (isset($data['valid_dates'])) {
            $offer->start_date = $data['valid_dates'][0];
            $offer->end_date = $data['valid_dates'][1];
        }

        if (isset($data['service_id'])) {
            $offer->service_id = $data['service_id'];
        }

        $offer->save();

        return $offer;
    }
    public function getAll($perPage)
    {
        // $hotelId = $this->getHotelIdFromAuth();

        return Offer::with('service')
            // ->whereHas('service', fn($q) => $q->where('hotel_id', $hotelId))
            ->whereHas('service')
            ->latest()
            ->paginate($perPage);
    }

    public function get($id)
    {
        // $hotelId = $this->getHotelIdFromAuth();

        $offer = Offer::with('service')
            ->where('id', $id)
            // ->whereHas('service', fn($q) => $q->where('hotel_id', $hotelId))
            ->whereHas('service')
            ->firstOrFail();
        if (!$offer)
            throw new ModelNotFoundException('Offer not found.');

        return $offer;
    }

    public function delete($id)
    {
        // $hotelId = $this->getHotelIdFromAuth();

        $offer = Offer::where('id', $id)
            // ->whereHas('service', fn($q) => $q->where('hotel_id', $hotelId))
            ->whereHas('service')
            ->firstOrFail();

        $offer->delete();
    }
}
