<?php

namespace App\Http\Repository\V1\Api;
use App\Models\Guest;
use App\Models\HotelLocation;
use App\Models\Restaurant;
use App\Models\RestaurantOrder;
use App\Models\RestaurantReservation;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Utils\FileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Schedule;
use Exception;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
class RestaurantRepository
{
    public function showRestaurantRepository(Request $request)
    {
        // $locale = app()->getLocale();
        $per_page = $request->input('per_page', 10);
        $hotelId = $request->header('hotel_id') ;
        if (!$hotelId||!is_numeric($hotelId) || !HotelLocation::find($hotelId)) {
            return ['status' => 'hotel_not_found'];
        }
        // Get only currently open restaurants
        $currentDay = strtolower(Carbon::now('Africa/Cairo')->locale('en')->translatedFormat('l'));
        // $currentTime = Carbon::now()->format('H:i');
        $currentTime = Carbon::now('Africa/Cairo')->format('H:i');
        $restaurants = Restaurant::with('schedules','exceptions', 'menuCategories.menuItems')
            ->where('hotel_id', $hotelId)
            ->whereHas('schedules', function ($q) use ($currentDay, $currentTime) {
                $q->where('day_of_week', $currentDay)
                    ->where('work_from', '<=', $currentTime)
                    ->where('work_to', '>=', $currentTime);
            })
            ->paginate($per_page);

        if ($restaurants->isEmpty()) {
            return ['status' => 'not_found'];
        }

        // $data = $restaurants->through(function ($restaurant) use ($locale) {
        //     return [
        //         'restaurant_name' => $restaurant->getTranslation('name', $locale),
        //         'restaurant_image_url' => $restaurant->image_url,
        //         'hotel_id' => $restaurant->hotel_id,
        //         'restaurant_cuisine' => $restaurant->getTranslation('cuisine', $locale),
        //         'schedules' => $restaurant->schedules->map(function ($schedule) {
        //             return [
        //                 'day_of_week' => $schedule->day_of_week,
        //                 'work_from' => $schedule->work_from,
        //                 'work_to' => $schedule->work_to,
        //             ];
        //         }),
        //     ];
        // });

        return [
            'status' => 'success',
            'data' => $restaurants,
        ];
    }

    public function storeRestaurantRepository(Request $request)
    {
        try {
            if (!$request->hasFile('restaurant_image')) {
                return ['status' => 'image_not_found'];
            }

            $imagePath = FileUpload::uploadImageOnLocal($request->file('restaurant_image'), 'Restaurant');
            DB::beginTransaction();
            $record = Restaurant::create([
                'name' => [
                    'en' => $request->input('restaurant_name.en'),
                    'ar' => $request->input('restaurant_name.ar'),
                ],
                'image_url' => $imagePath,
                'cuisine' => [
                    'en' => $request->input('restaurant_cuisine.en'),
                    'ar' => $request->input('restaurant_cuisine.ar'),
                ],
                'hotel_id' => $request->input('hotel_id'),
            ]);

            // Check and store optional schedules
            if ($request->has('schedules') && is_array($request->schedules)) {
                foreach ($request->schedules as $schedule) {
                    if (
                        isset($schedule['day_of_week']) &&
                        isset($schedule['work_from']) &&
                        isset($schedule['work_to'])
                    ) {
                        $record->schedules()->create([
                            'day_of_week' => $schedule['day_of_week'],
                            'work_from' => $schedule['work_from'],
                            'work_to' => $schedule['work_to'],
                        ]);
                    }
                }
            }

            DB::commit();
            return [
                'status' => 'success',
                'data' => $record,
            ];

        } catch (QueryException $e) {
            return ['status' => 'db_error', 'message' => $e->getMessage()];


        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];

        }
    }
    public function getRestaurantMenu($restaurantId, Request $request)
    {
        $locale = app()->getLocale();
        $per_page = $request->input('per_page', 10);
        $hotelId = $request->header('hotel_id');
        if (!$hotelId||!is_numeric($hotelId) || !HotelLocation::find($hotelId)) {
            return ['status' => 'hotel_not_found'];
        }
        $restaurant = Restaurant::where('id', $restaurantId)
            ->where('hotel_id', $hotelId)
            ->first();
        if (!$restaurant) {
            return ['status' => 'not_found'];
        }

        // Eager load menu categories and their items
        $categories = $restaurant->menuCategories()
            ->with('menuItems')
            ->paginate($per_page);

        return [
            'status' => 'success',
            'categories' => $categories,
        ];
    }

    public function reserve(array $data)
    {
        return DB::transaction(function () use ($data) {

            $restaurant = Restaurant::with(['schedules', 'exceptions'])
                ->where('id', $data['restaurant_id'])
                ->where('hotel_id', $data['hotel_id'])
                ->firstOrFail();

            // $guest = Guest::findOrFail($data['guest_id']);
            // if (!$guest) {
            //     throw new ModelNotFoundException("Guest not found.");
            // }

            $guest = Auth::guard('guest')->user();
            if (!$guest) {
                throw new UnauthorizedException("Unauthenticated guest.");
            }

            if (
                ($data['order_type'] === 'dining_in' && !$restaurant->in_dining) ||
                ($data['order_type'] === 'room_service' && !$restaurant->room_service)
            ) {
                throw new \App\Exceptions\ValidationException("This restaurant does not support the selected order type: {$data['order_type']}.");
            }

            $reservationTime = Carbon::parse($data['reservation_time']);
            $dayOfWeek = strtolower($reservationTime->format('l'));
            $time = $reservationTime->format('H:i');

            $isOpen = $restaurant->schedules()
                ->where('day_of_week', $dayOfWeek)
                ->where('work_from', '<=', $time)
                ->where('work_to', '>=', $time)
                ->exists();

            if (!$isOpen) {
                throw new \App\Exceptions\ValidationException("Restaurant is not open at the specified reservation time.");
            }

            $isException = $restaurant->exceptions()
                ->where('date', $reservationTime->toDateString())
                ->where('exception_from', '<=', $time)
                ->where('exception_to', '>=', $time)
                ->exists();

            if ($isException) {
                throw new \App\Exceptions\ValidationException("Restaurant is not open at the specified reservation time.");
            }

            $reservation = RestaurantReservation::create([
                'restaurant_id' => $restaurant->id,
                'guest_id' => $guest->id,
                'order_type' => $data['order_type'], // 'dining_in' or 'room_service'
                'reservation_time' => $reservationTime,
                'notes' => $data['notes'] ?? null,
            ]);

            $validMenuItemIds = $restaurant->menuCategories
                ->flatMap(fn($category) => $category->menuItems->pluck('id'))
                ->toArray();

            foreach ($data['items'] as $item) {
                if (!in_array($item['menu_item_id'], $validMenuItemIds)) {
                    throw new \App\Exceptions\ValidationException("Menu item ID {$item['menu_item_id']} does not belong to this restaurant.");
                }

                RestaurantOrder::create([
                    'reservation_id' => $reservation->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
            return $reservation->load('restaurant', 'restaurantOrders.menuItem');
        });
    }

    public function getRestaurantReservationsForGuest(array $filters = [])
    {
        $guest = Auth::guard('guest')->user();

        if (!$guest) {
            throw new AuthenticationException("Unauthenticated.");
        }
        // $guest = Guest::where('id', $guest->id)
        //     ->firstOrFail();
        // if (!$guest)
        //     throw new ModelNotFoundException("Guest Not Found.");

        // $reservations = RestaurantReservation::with(['guest', 'restaurantOrders.menuItem'])
        //     ->where('guest_id', $guest->id)
        //     ->orderBy('reservation_time', 'desc')
        //     ->paginate(10);

        // return $reservations;

        $query = RestaurantReservation::with(['guest', 'restaurantOrders.menuItem'])
        ->where('guest_id', $guest->id);

    if (isset($filters['order_type'])) {
        $query->whereHas('restaurantOrders', function ($q) use ($filters) {
            $q->where('order_type', $filters['order_type']);
        });
    }

    if (isset($filters['reservation_time'])) {
        $query->whereDate('reservation_time', $filters['reservation_time']);
    }

    return $query->orderBy('reservation_time', 'desc')->paginate(10);
    }

}
