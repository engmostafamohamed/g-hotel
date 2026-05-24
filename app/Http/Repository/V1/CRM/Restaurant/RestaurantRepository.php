<?php

namespace App\Http\Repository\V1\CRM\Restaurant;

use App\DataTransferObjects\RestaurantDTOs\RestaurantDTO;
use App\DataTransferObjects\RestaurantDTOs\AvailabilityDTO;
use App\Models\Restaurant;
use App\Models\RestaurantReservation;
use App\Traits\UsesHotelScope;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\DB;
class RestaurantRepository
{
    use UsesHotelScope;
    public function list(array $filters): LengthAwarePaginator
    {
        $query = Restaurant::query();

        // Always apply obligatory hotel_id from middleware if it exists
        if ($obligatoryHotelId = current_hotel_id()) {
            $query->where('hotel_id', $obligatoryHotelId);
        } elseif (!empty($filters['hotel_id'])) {
            // Allow optional hotel_id filter when no obligatory one is set
            // if obligatory one is set and user still sends an optional one and that optional one is not equal to the obligatory one, throw an unauthorized exception.
            $query->where('hotel_id', $filters['hotel_id']);
        }

        if (!empty($filters['cuisine'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('cuisine->en', 'LIKE', '%' . $filters['cuisine'] . '%')
                    ->orWhere('cuisine->ar', 'LIKE', '%' . $filters['cuisine'] . '%');
            });
        }

        if (!empty($filters['currently_open'])) {
            $query->whereHas('schedules', function ($q) {
                $now = now();
                $dayOfWeek = $now->dayOfWeek;
                $time = $now->format('H:i:s');

                $q->where('day_of_week', $dayOfWeek)
                    ->where('work_from', '<=', $time)
                    ->where('work_to', '>=', $time);
            });
        }

        $perPage = request()->query('per_page', 10);
        return $query->with(['schedules','exceptions', 'menuCategories.menuItems'])->paginate((int) $perPage);
    }

    // public function listUnpaginated(array $filters): Collection
    // {
    //     $query = Restaurant::query();

    //     if (!empty($filters['hotel_id'])) {
    //         $query->where('hotel_id', $filters['hotel_id']);
    //     }

    //     if (!empty($filters['cuisine'])) {
    //         $query->where(function ($q) use ($filters) {
    //             $q->where('cuisine->en', 'LIKE', '%' . $filters['cuisine'] . '%')
    //                 ->orWhere('cuisine->ar', 'LIKE', '%' . $filters['cuisine'] . '%');
    //         });
    //     }

    //     if (!empty($filters['currently_open'])) {
    //         $query->whereHas('schedules', function ($q) {
    //             $now = now();
    //             $dayOfWeek = $now->dayOfWeek;
    //             $time = $now->format('H:i:s');

    //             $q->where('day_of_week', $dayOfWeek)
    //                 ->where('work_from', '<=', $time)
    //                 ->where('work_to', '>=', $time);
    //         });
    //     }

    //     return $query->with(['schedules', 'menuCategories.menuItems'])->get();
    // }


    public function create(RestaurantDTO $dto): Restaurant
    {
        $restaurant = new Restaurant();
        $restaurant->setTranslations('name', $dto->name);
        $restaurant->setTranslations('cuisine', $dto->cuisine);
        $restaurant->hotel_id = $dto->hotel_id;

        if ($dto->imagePath) {
            $restaurant->image_url = $dto->imagePath;
        }

        $restaurant->save();
        return $restaurant;
    }

    public function update(int $id, RestaurantDTO $dto): Restaurant
    {
        $restaurant = $this->find($id);

        if (!empty($dto->name)) {
            $restaurant->setTranslations('name', $dto->name);
        }
        if (!empty($dto->cuisine)) {
            $restaurant->setTranslations('cuisine', $dto->cuisine);
        }
        if ($dto->hotel_id) {
            $restaurant->hotel_id = $dto->hotel_id;
        }
        if ($dto->imagePath) {
            $restaurant->image_url = $dto->imagePath;
        }

        $restaurant->save();

        return $restaurant;
    }


    public function delete(int $id): void
    {
        $restaurant = $this->find($id);
        $restaurant->delete();
    }

    public function find(int $id): Restaurant
    {
        return Restaurant::with(['schedules', 'menuCategories.menuItems'])
            ->when(current_hotel_id(), function ($q, $hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->findOrFail($id);
    }

    public function availability(AvailabilityDTO $dto)
    {
        try {
            DB::beginTransaction();
            $restaurant = Restaurant::where('id', $dto->restaurant_id)
                ->where('hotel_id', $dto->hotel_id)
                ->whereNull('deleted_at')
                ->first();
            // Update in_dining and room_service if set
            $updateData = [];
            if (!empty($dto->in_dining)) {
                $updateData['in_dining'] = $dto->in_dining;
            }

            if (!empty($dto->room_service)) {
                $updateData['room_service'] = $dto->room_service;
            }

            if (!empty($updateData)) {
                $restaurant->update($updateData);
            }
            $hasSchedules = !empty($dto->schedules);
            $hasExceptions = !empty($dto->exception_dates);

            if (empty($updateData) && !$hasSchedules && !$hasExceptions) {
                DB::rollBack();
                return ['status' => 'no_changes']; // <-- Custom flag
            }

            // Save schedules
            if (!empty($dto->schedules)) {
                foreach ($dto->schedules as $schedule) {
                    $restaurant->schedules()->create([
                        'day_of_week' => $schedule['day_of_week'],
                        'work_from' => $schedule['work_from'],
                        'work_to' => $schedule['work_to'],
                    ]);
                }
            }

            // Save exception dates
            if (!empty($dto->exception_dates)) {
                foreach ($dto->exception_dates as $exception) {
                    $restaurant->exceptions()->create([
                        'date' => $exception['date'],
                        'exception_from' => $exception['exception_from'],
                        'exception_to' => $exception['exception_to'],
                    ]);
                }
            }

            DB::commit();

            return [
                'status' => 'success',
                'data' => [],
            ];

        } catch (QueryException $e) {
            DB::rollBack();
            return ['status' => 'db_error', 'message' => $e->getMessage()];
        } catch (Exception $e) {
            DB::rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getRestaurantReservationsForRestaurant($restaurantId, array $filters = [])
    {
        $hotelId = $this->getHotelIdFromAuth();
        $restaurant = Restaurant::where('id', $restaurantId)
            ->firstOrFail();

        if (!$restaurant)
            throw new ModelNotFoundException("Restaurant Not Found.");

        if ($restaurant->hotel_id !== $hotelId) {
            throw new AuthorizationException("You do not have access to this restaurant.");
        }
        // $reservations = RestaurantReservation::with(['guest', 'restaurantOrders.menuItem'])
        //     ->where('restaurant_id', $restaurant->id)
        //     ->orderBy('reservation_time', 'desc')
        //     ->paginate(10);

        // return $reservations;

        $query = RestaurantReservation::with(['guest', 'restaurantOrders.menuItem'])
            ->where('restaurant_id', $restaurant->id);

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
