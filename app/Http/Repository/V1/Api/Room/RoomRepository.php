<?php

namespace App\Http\Repository\V1\Api\Room;

use App\DataTransferObjects\RoomDTOs\BulkRoomDTO;
use App\Models\Room;
use App\Models\RoomType;
use App\Contracts\Room\Api\RoomRepositoryInterface;
use App\DataTransferObjects\RoomDTOs\Api\BookRoomDTO;
use App\DataTransferObjects\RoomDTOs\Api\RoomDTO;
use App\Models\Bed;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Http\Request;
use App\Utils\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\View;
use Illuminate\Auth\Access\AuthorizationException;

class RoomRepository implements RoomRepositoryInterface
{
    // public function findRooms(array $filters)
    // {
    //     $query = Room::with([
    //         'roomType',
    //         'roomType.views',
    //         'roomType.category',
    //         'roomType.category.beds',
    //         'roomType.category.features',
    //     ]);

    //     if (!empty($filters['room_type_id'])) {
    //         $query->where('room_type_id', $filters['room_type_id']);
    //     }

    //     if (!empty($filters['room_number'])) {
    //         $query->where('room_number', 'like', '%' . $filters['room_number'] . '%');
    //     }

    //     return $query->paginate(10);
    // }

    // public function findRooms(RoomDTO  $filters)
    // {
    //     $fromDate = $filters->from_date;
    //     $hotel_id = $filters->hotel_id;
    //     $toDate = $filters->to_date;
    //     $adults = $filters->adults;
    //     $children = $filters->children;
    //     $roomViewIds = $filters->room_view_ids;

    //     $query = RoomType::with([
    //         'seasonalPrices',
    //         'views',
    //         'category',
    //         'category.beds',
    //         'category.features',
    //     ]);
    //     // Filter by seasonal price range
    //     $query->whereHas('seasonalPrices', function ($q) use ($fromDate, $toDate) {
    //         $q->where('from', '<=', $fromDate)
    //         ->where('to', '>=', $toDate);
    //     });

    //     // Filter by adult and children capacity
    //     $query->whereHas('category', function ($q) use ($adults, $children,$hotel_id) {
    //         $q->where('max_adults', '>=', $adults)
    //         ->where('max_children', '>=', $children)
    //         ->where('hotel_id', $hotel_id);
    //     });

    //     $query->whereDoesntHave('bookings', function ($q) use ($fromDate, $toDate) {
    //         $q->where('arrival_date', '<', $toDate)
    //         ->where('departure_date', '>', $fromDate);
    //     });
    //     // Optional filters
    //     if ($filters->room_type_id) {
    //         $query->where('room_type_id', $filters->room_type_id);
    //     }

    //     if ($filters->min_price || $filters->max_price) {
    //         $query->whereHas('roomType', function ($q) use ($filters) {
    //             if ($filters->min_price) {
    //                 $q->where('base_price', '>=', $filters->min_price);
    //             }
    //             if ($filters->max_price) {
    //                 $q->where('base_price', '<=', $filters->max_price);
    //             }
    //         });
    //     }

    //     if (is_array($roomViewIds) && count($roomViewIds) > 0) {
    //         $query->whereHas('roomType.views', function ($q) use ($roomViewIds) {
    //             $q->whereIn('views.id', $roomViewIds);
    //         });
    //     }

    //     $rooms = $query->paginate(10);

    //     // Append final price (seasonal or base)
    //     $rooms->getCollection()->transform(function ($room) use ($fromDate, $toDate) {
    //         $seasonal = null;

    //         if (
    //             $room->roomType &&
    //             $room->roomType->seasonalPrices &&
    //             $room->roomType->seasonalPrices->count()
    //         ) {
    //             $seasonal = $room->roomType->seasonalPrices
    //                 ->where('from', '<=', $fromDate)
    //                 ->where('to', '>=', $toDate)
    //                 ->first();
    //         }

    //         $room->final_price = $seasonal
    //             ? $seasonal->price
    //             : ($room->roomType->base_price ?? 0);

    //         return $room;
    //     });

    //     if ($rooms->isEmpty()) {
    //         return ['status' => 'room_not_found'];
    //     }

    //     return $rooms;
    // }

    public function findRooms(RoomDTO $filters)
    {
        $from = $filters->from_date;
        $to = $filters->to_date;
        $hotelId = current_hotel_id();

        if (!$hotelId) {
            throw new AuthorizationException(__('room.hotel_context_required'));
        }

        $query = RoomType::query()
            ->with(['rooms', 'rooms.bookings', 'views', 'category', 'seasonalPrices'])
            ->whereHas('category', fn($q) => $q->where('hotel_id', $hotelId))
            //adult and children capacity filter commented to allow showing all room types, even those that cannot accommodate the requested number, since not always will one room type accommodate the entire party
            // this can be handled at the booking stage by checking availability across multiple room types, or later display all possible combinations of room types that can accommodate the party or exceed it.
            // ->whereHas('category', fn($q) =>
            //     $q->where('max_adults', '>=', $filters->adults)
            //       ->where('max_children', '>=', $filters->children ?? 0))
            ;

        // Filter by categories (room types)
        if (!empty($filters->room_type_ids)) {
            $query->whereIn('category_id', $filters->room_type_ids);
        }

        // Filter by views (any of selected views)
        if (!empty($filters->room_view_ids)) {
            $query->whereHas('views', fn($q) => $q->whereIn('views.id', $filters->room_view_ids));
        }

        // Filter by features (all must be present)
        if (!empty($filters->feature_ids)) {
            foreach ($filters->feature_ids as $featureId) {
                $query->whereHas('category.features', function ($q) use ($featureId) {
                    $q->where('features.id', $featureId);
                });
            }
        }

        // Exclude blackout dates
        $query->whereDoesntHave('category.blackout_dates', function ($q) use ($from, $to) {
            $q->where(function ($q2) use ($from, $to) {
                $q2->whereBetween('start_date', [$from, $to])
                    ->orWhereBetween('end_date', [$from, $to])
                    ->orWhere(function ($q3) use ($from, $to) {
                        $q3->where('start_date', '<=', $from)
                        ->where('end_date', '>=', $to);
                    });
            });
        });

        // Fetch results to compute availability & prices
        $roomTypes = $query->get();

        $nights = Carbon::parse($from)->diffInDays(Carbon::parse($to));

        // Compute availability & prices
        $roomTypes = $roomTypes->map(function ($type) use ($from, $to, $nights) {
            $total = $type->rooms->count();

            // Determine booked count
            $booked = $type->rooms->filter(fn($room) =>
                $room->bookings->where('arrival_date', '<', $to)
                            ->where('departure_date', '>', $from)
                            ->isNotEmpty()
            )->count();

            $availableCount = $total - $booked;

            if ($availableCount <= 0) {
                return null; // exclude fully booked
            }

            $fromDate = Carbon::parse($from);
            $totalPrice = 0;

            for ($i = 0; $i < $nights; $i++) {
                $currentDate = $fromDate->copy()->addDays($i);

                $seasonal = $type->seasonalPrices
                    ->first(fn($sp) => $currentDate->between($sp->from, $sp->to));

                $totalPrice += $seasonal?->price ?? $type->base_price;
            }

            $type->final_price = $totalPrice;
            $type->available_rooms = $availableCount;

            return $type;
        })->filter();

        // Apply price range filter
        if ($filters->min_price || $filters->max_price) {
            $min = $filters->min_price ?? 0;
            $max = $filters->max_price ?? PHP_INT_MAX;

            $roomTypes = $roomTypes->filter(fn($type) =>
                $type->final_price >= $min && $type->final_price <= $max
            );
        }

        // Sorting
        $sort = $filters->getSort();
        $sorted = $roomTypes->sortBy(
            fn($type) => $type->{$sort['field']} ?? $type->final_price,
            SORT_REGULAR,
            strtolower($sort['dir']) === 'desc'
        )->values(); // reset keys

        // Manual pagination (since this is now a Collection)
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);

        $paginated = new LengthAwarePaginator(
            $sorted->forPage($page, $perPage),
            $sorted->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return $paginated;
    }



    public function bookRoom(BookRoomDTO $dto)
    {
        try {
            
            $hotel_id = current_hotel_id();
            if (!$hotel_id) {
                throw new AuthorizationException(__('room.hotel_context_required'));
            }

            // need to validate room type passed in the dto belongs to the hotel in the context

            // Ensure no blackout dates overlap for this room type’s category
            $availableRoomIds = Room::where('room_type_id', $dto->roomType_id)
                ->whereHas('roomType.category', function ($q) use ($dto) {
                    $q->whereDoesntHave('blackout_dates', function ($b) use ($dto) {
                        $b->where(function ($q2) use ($dto) {
                            $q2->whereBetween('start_date', [$dto->checkIn, $dto->checkOut])
                                ->orWhereBetween('end_date', [$dto->checkIn, $dto->checkOut])
                                ->orWhere(function ($q3) use ($dto) {
                                    $q3->where('start_date', '<=', $dto->checkIn)
                                        ->where('end_date', '>=', $dto->checkOut);
                                });
                        });
                    });
                })
                ->whereDoesntHave('bookings', function ($q) use ($dto) {
                    $q->where('arrival_date', '<', $dto->checkOut)
                    ->where('departure_date', '>', $dto->checkIn)
                    ->where('checked_out', false);
                })
                ->pluck('id');

            $roomId = $availableRoomIds->first();

            if (!$roomId) {
                return ['status' => 'no_available_rooms'];
            }

            //calculate total price later to return with the booking confirmation

            $booking = Booking::create([
                'hotel_id'     => $hotel_id,
                'guest_id'     => $dto->guest_id,
                'room_id'      => $roomId,
                'booking_date' => now()->toDateString(),
                'booking_time' => now()->toTimeString(),
                'arrival_date' => $dto->checkIn,
                'arrival_time' => '14:00:00',
                'departure_date' => $dto->checkOut,
                'departure_time' => '12:00:00',
                'num_adults'   => $dto->adults,
                'num_children' => $dto->children,
                'special_reg'  => $dto->upsells ? implode(',', $dto->upsells) : null,
                'loyalty_points_earned'   => 0,
                'loyalty_points_redeemed' => 0,
                'checked_out'  => false,
                // add 'total_price' to database and store total price after calculation and applying discounts if any
            ]);

            return $booking;

        } catch (AuthorizationException $e) {
            return [
                'status'  => 'unauthorized',
                'message' => __('room.hotel_context_required'),
            ];

        } catch (\Throwable $e) {
            return [
                'status'  => 'error',
                'message' => 'Booking could not be created',
                'debug'   => $e->getMessage(),
            ];
        }
    }


    public function getCategoriesForFilter(int $hotelId): Collection
    {
        return Category::select('id', 'name')
            ->where('hotel_id', $hotelId)
            ->get();
    }

    public function getFeaturesForFilter(int $hotelId): Collection
    {
        return Feature::select('id', 'name')
            ->where('hotel_id', $hotelId)
            ->get();
    }

    public function getViewsForFilter(): Collection
    {
        return View::select('id', 'type')->get();
    }
}

