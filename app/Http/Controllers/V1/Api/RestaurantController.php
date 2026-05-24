<?php

namespace App\Http\Controllers\V1\Api;
use App\Exceptions\ValidationException;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\Restaurant\StoreRestaurantReservationRequest;
use App\Http\Resources\V1\Api\Restaurant\MenuCategoryResource;
use App\Http\Resources\V1\Api\Restaurant\RestaurantReservationResource;
use App\Http\Resources\V1\Api\Restaurant\PaginatedRestaurantListResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\V1\Api\RestaurantReqest;
use App\Http\Repository\V1\Api\RestaurantRepository;
class RestaurantController extends Controller
{
    protected $restaurant;

    public function __construct(RestaurantRepository $restaurantRepository)
    {
        $this->restaurant = $restaurantRepository;
    }
    public function showRestaurant(Request $request)
    {
        $result = $this->restaurant->showRestaurantRepository($request);
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('restaurant.data_not_found'), [], 200);
        }
        if ($result['status'] === 'hotel_not_found') {
            return ApiResponse::error(__('restaurant.hotel_id_not_found'), [], 200);
        }

        return ApiResponse::success(
            __('restaurant.data_fetched_successfully'),
                new PaginatedRestaurantListResource($result['data']),
            200
        );
    }
    public function addRestaurant(RestaurantReqest $request)
    {
        $result = $this->restaurant->storeRestaurantRepository($request);

        if ($result['status'] === 'image_not_found') {
            return ApiResponse::error(__('restaurant.image_not_found'), [], 200);
        }

        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('restaurant.error_happend'), [], 500);
        }

        // success
        return ApiResponse::success(
            __('restaurant.data_added_successfully'),
            [],
            201
        );
    }

    public function getRestaurantMenu($id, Request $request)
    {
        try {
            $result = $this->restaurant->getRestaurantMenu($id, $request);

            if ($result['status'] === 'not_found') {
                return ApiResponse::error(__('restaurant.menu_data_not_found'), [], 200);
            }
            if ($result['status'] === 'hotel_not_found') {
                return ApiResponse::error(__('restaurant.hotel_id_not_found'), [], 200);
            }

            return ApiResponse::success(
                __('restaurant.menu_fetched_successfully'),
                MenuCategoryResource::collection($result['categories']),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }

    }

    public function reserve(StoreRestaurantReservationRequest $request)
    {
        try {
            $reservation = $this->restaurant->reserve($request->validated());

            return response()->json([
                'success' => true,
                'statusCode' => 201,
                'message' => 'Reservation created successfully',
                'data' => new RestaurantReservationResource($reservation)
            ], 201);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Reservation could not be created.',
                'errors' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Reservation could not be created.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }

    }

    public function getRestaurantReservationsForGuest(Request $request)
    {
        try {
            $filters = $request->only(['order_type', 'reservation_time']);
            $reservations = $this->restaurant->getRestaurantReservationsForGuest($filters);

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Reservations for guest fetched successfully',
                'data' => RestaurantReservationResource::collection($reservations),
                'meta' => [
                    'current_page' => $reservations->currentPage(),
                    'total' => $reservations->total(),
                    'per_page' => $reservations->perPage(),
                ]
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Reservations could not be fetched for guest.',
                'errors' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Reservation could not be fetched for guest.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

}
