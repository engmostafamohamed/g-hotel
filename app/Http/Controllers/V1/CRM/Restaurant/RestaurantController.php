<?php

namespace App\Http\Controllers\V1\CRM\Restaurant;

use App\DataTransferObjects\RestaurantDTOs\AvailabilityDTO;
use App\DataTransferObjects\RestaurantDTOs\RestaurantDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Restaurant\RestaurantRequest;
use App\Http\Requests\V1\CRM\Restaurant\StoreRestaurantRequest;
use App\Http\Requests\V1\CRM\Restaurant\UpdateRestaurantRequest;
use App\Http\Resources\V1\Api\Restaurant\RestaurantReservationResource;
use App\Http\Resources\V1\CRM\Restaurant\PaginatedRestaurantListResource;
use App\Http\Resources\V1\CRM\Restaurant\RestaurantResource;
use App\Http\Resources\V1\CRM\Restaurant\UnpaginatedRestaurantListResource;
use App\Services\V1\CRM\RestaurantService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class RestaurantController extends Controller
{
    public function __construct(private RestaurantService $service) {}

    public function index(Request $request)
    {
        try {
            $restaurants = $this->service->list($request);

            return ApiResponse::success(
                __('restaurant.fetched'),
                new PaginatedRestaurantListResource($restaurants),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('restaurant.unexpected'), [$e->getMessage()], 500);
        }
    }

    // public function indexUnPaginated(Request $request)
    // {
    //     try {
    //         $restaurants = $this->service->listUnpaginated($request);
    //
    //         return ApiResponse::success(
    //             __('restaurant.fetched'),
    //             UnpaginatedRestaurantListResource::collection($restaurants),
    //             200
    //         );
    //     } catch (Throwable $e) {
    //         return ApiResponse::error(__('restaurant.unexpected'), [$e->getMessage()], 500);
    //     }
    // }

    public function store(StoreRestaurantRequest $request)
    {
        try {
            $dto = RestaurantDTO::fromRequest($request);
            $restaurant = $this->service->create($dto);

            return ApiResponse::success(
                __('restaurant.created'),
                new RestaurantResource($restaurant),
                201
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('restaurant.unexpected_create'), [$e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $restaurant = $this->service->find($id);

            return ApiResponse::success(
                __('restaurant.fetched_single'),
                new RestaurantResource($restaurant),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('restaurant.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('restaurant.unexpected'), [$e->getMessage()], 500);
        }
    }

    public function update(UpdateRestaurantRequest $request, int $id)
    {
        try {
            $dto = RestaurantDTO::fromRequest($request);
            $restaurant = $this->service->update($id, $dto);

            return ApiResponse::success(
                __('restaurant.updated'),
                new RestaurantResource($restaurant),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('restaurant.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('restaurant.unexpected_update'), [$e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->service->delete($id);

            return ApiResponse::success(__('restaurant.deleted'), [], 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('restaurant.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('restaurant.unexpected_delete'), [$e->getMessage()], 500);
        }
    }

    public function availability(RestaurantRequest $request, int $id)
    {
        $result = $this->service->availability(AvailabilityDTO::fromRequest($request), $id);
    
        // if ($result['status'] === 'Restaurant_not_found') {
        //     return ApiResponse::error(__('restaurant.restaurant_id_not_found'), [], 422);
        // }
    
        if ($result['status'] === 'no_changes') {
            return ApiResponse::error(__('restaurant.no_changes'), [], 422); // custom message
        }
    
        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('restaurant.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('restaurant.data_added_successfully'),
            [],
            201
        );
    }

    public function getRestaurantReservations(Request $request, $id)
    {
        try {
            $filters = $request->only(['order_type', 'reservation_time']);
            $reservations = $this->service->getRestaurantReservationsForRestaurant($id, $filters);
            return response()->json([
                'success' => true,
                'statusCode' => 201,
                'message' => 'Reservations for restaurant fetched successfully',
                'data' => RestaurantReservationResource::collection($reservations),
                'meta' => [
                    'current_page' => $reservations->currentPage(),
                    'total' => $reservations->total(),
                    'per_page' => $reservations->perPage(),
                ]
            ], 201);
    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Reservations could not be fetched for restaurant.',
                'errors' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Reservation could not be fetched for restaurant.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
}


