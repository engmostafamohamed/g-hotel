<?php

namespace App\Http\Repository\V1\CRM\Booking;

use App\DataTransferObjects\V1\CRM\Booking\BookRoomDTO;
use App\Models\{Booking, Room, RoomType};
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class BookingRepository
{
    public function getTotalNightsByGuest(int $guestId): Collection
    {
        $query = Booking::query()
            ->selectRaw('hotel_id, SUM(DATEDIFF(departure_date, arrival_date)) as total_nights')
            ->where('guest_id', $guestId);

        if ($obligatoryHotelId = current_hotel_id()) {
            $query->where('hotel_id', $obligatoryHotelId);
        }

        return $query->groupBy('hotel_id')->pluck('total_nights', 'hotel_id');
    }

    public function createBookingForGuest(BookRoomDTO $dto, int $employeeId)
    {
        return DB::transaction(function () use ($dto, $employeeId) {
            $summary = [
                'booked' => [],
                'unavailable' => [],
            ];

            $allRoomIds = collect();
            $grandTotal = 0;

            $checkIn  = $dto->checkIn;
            $checkOut = $dto->checkOut;
            $totalNights = Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));

            // Loop through each requested room type
            foreach ($dto->roomTypes as $roomTypeData) {
                $roomTypeId = $roomTypeData['id'];
                $count = $roomTypeData['count'];

                $roomType = RoomType::findOrFail($roomTypeId);

                $availableRoomIds = $this->getAvailableRooms($roomTypeId, $checkIn, $checkOut, $count);

                if ($availableRoomIds->count() < $count) {
                    $summary['unavailable'][] = [
                        'room_type_id' => $roomTypeId,
                        'room_type_name' => $roomType->name,
                        'requested' => $count,
                        'available' => $availableRoomIds->count(),
                    ];
                    continue;
                }

                $pricePerRoom = $this->calculateTotalBookingPrice($roomTypeId, $checkIn, $checkOut);
                $typeTotal = $pricePerRoom * $count;

                $allRoomIds = $allRoomIds->merge($availableRoomIds);
                $grandTotal += $typeTotal;

                $summary['booked'][] = [
                    'room_type_id'   => $roomTypeId,
                    'room_type_name' => $roomType->name,
                    'booked_rooms'   => $availableRoomIds->count(),
                    'nights'         => $totalNights,
                    'price_per_room' => $pricePerRoom,
                    'total_price'    => $typeTotal,
                ];
            }

            // If any room types unavailable — do NOT create booking
            if (!empty($summary['unavailable'])) {
                // Transaction will rollback automatically if we throw an exception
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => __('booking.booking_unsuccessful'),
                    'unavailable' => $summary['unavailable']
                ];
            }

            // All room types available — create booking
            $booking = Booking::create([
                'hotel_id'     => $dto->hotel_id,
                'guest_id'     => $dto->guest_id,
                'booking_date' => now()->toDateString(),
                'booking_time' => now()->toTimeString(),
                'arrival_date' => $checkIn,
                'arrival_time' => '14:00:00',
                'departure_date' => $checkOut,
                'departure_time' => '12:00:00',
                'num_adults'   => $dto->adults,
                'num_children' => $dto->children,
                'loyalty_points_earned'   => $grandTotal * 0.1, // 10% of total price for now
                'loyalty_points_redeemed' => 0,
                'checked_out'  => true, // (paid) static for now — checkout process not implemented
                'created_by'   => $employeeId,
                'total_price'  => $grandTotal,
            ]);

            $booking->rooms()->attach($allRoomIds);

            $bookedRooms = $booking->rooms()
                ->with(['roomType:id,name'])
                ->get(['rooms.id', 'rooms.room_number', 'rooms.room_type_id'])
                ->map(function ($room) {
                    return [
                        'id' => $room->id,
                        'room_number' => $room->room_number,
                        'room_type' => [
                            'id' => $room->roomType->id,
                            'name' => $room->roomType->name,
                        ],
                    ];
                });


            //add point to guest loyalty account here later

            return [
                'success' => true,
                'booking_id' => $booking->id,
                'total_price' => $grandTotal,
                'total_nights' => $totalNights,
                'booked' => $summary['booked'],
                'unavailable' => [],
                'rooms' => $bookedRooms,
            ];
        });
    }


    private function getAvailableRooms(int $roomTypeId, string $checkIn, string $checkOut, int $count): Collection
    {
        return Room::where('room_type_id', $roomTypeId)
            ->whereNotIn('id', function ($sub) use ($checkIn, $checkOut) {
                $sub->select('booking_room.room_id')
                    ->from('booking_room')
                    ->join('bookings', 'bookings.id', '=', 'booking_room.booking_id')
                    ->where('bookings.arrival_date', '<', $checkOut)
                    ->where('bookings.departure_date', '>', $checkIn)
                    ->where('bookings.checked_out', true);
            })
            ->limit($count)
            ->pluck('id');
    }



    private function calculateTotalBookingPrice(int $roomTypeId, string $checkIn, string $checkOut): float
    {
        $roomType = RoomType::with('seasonalPrices')->findOrFail($roomTypeId);
        $fromDate = Carbon::parse($checkIn);
        $toDate = Carbon::parse($checkOut);
        $nights = $fromDate->diffInDays($toDate);
        $totalPrice = 0;

        for ($i = 0; $i < $nights; $i++) {
            $currentDate = $fromDate->copy()->addDays($i);
            $seasonal = $roomType->seasonalPrices
                ->first(fn($sp) => $currentDate->between($sp->from, $sp->to));
            $totalPrice += $seasonal?->price ?? $roomType->base_price;
        }

        return $totalPrice;
    }

    //add index bookings here
    public function getFilteredBookings(
        ?int $hotelId,
        ?int $guestId,
        ?string $fromDate,
        ?string $toDate,
        string $sort = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Booking::with(['guest', 'rooms', 'hotel'])
            ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))
            ->when($guestId, fn($q) => $q->where('guest_id', $guestId))
            ->when(
                $fromDate && $toDate,
                fn($q) =>
                $q->whereBetween('booking_date', [$fromDate, $toDate])
            )
            ->orderBy('booking_date', $sort);

        return $query->paginate($perPage);
    }
}
