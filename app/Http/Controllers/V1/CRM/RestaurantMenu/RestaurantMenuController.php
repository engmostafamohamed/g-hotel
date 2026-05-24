<?php

namespace App\Http\Controllers\V1\CRM\RestaurantMenu;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\RestaurantMenu\ImportMenuRequest;
use App\Http\Requests\V1\CRM\RestaurantMenu\UpdateMenuItemRequest;
use App\Http\Resources\V1\CRM\RestaurantMenu\GetAllMenusResource;
use App\Http\Resources\V1\CRM\RestaurantMenu\MenuItemResource;
use App\Services\V1\CRM\RestaurantMenuService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Log;
class RestaurantMenuController extends Controller
{
    //
    public function __construct(protected RestaurantMenuService $service)
    {
    }

    public function import(ImportMenuRequest $request)
    {
        try {
            $result = $this->service->importMenuFromCSV(
                $request->input('restaurant_id'),
                $request->file('file'),
                $request->input('location'),
                $request->input('menu_type'),
                $request->boolean('update_existing', false)
            );
            // $result = $this->service->importMenuFromCSVZip($request);

            return response()->json([
                'success' => true,
                'statusCode' => '200',
                'message' => 'Restaurant Menu imported successfully.',
                'data' => $result,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => '400',
                'message' => 'Menu import failed',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function getAllMenus(Request $request)
    {
        try {
            $perPage = 10;
            $menus = $this->service->getFullMenuGroupedByRestaurant($perPage);

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Restaurant Menus fetched successfully.',
                'data' => GetAllMenusResource::collection($menus),
                'meta' => [
                    'current_page' => $menus->currentPage(),
                    'last_page' => $menus->lastPage(),
                    'total' => $menus->total(),
                    'per_page' => $menus->perPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Service Category could not be created.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }

    }
    public function getRestaurantMenu($restaurantId)
    {
        try {
            $restaurant = $this->service->getRestaurantMenuById($restaurantId);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Restaurant Menu fetched successfully.',
                'data' => new GetAllMenusResource($restaurant),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => '404',
                'message' => "Restaurant with ID {$restaurantId} not found."
            ], 404);
        }
    }

    public function deleteMenu($restaurantId)
    {
        try {
            $this->service->deleteRestaurantMenu($restaurantId);

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Restaurant menu deleted successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => "Restaurant with ID {$restaurantId} not found."
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => 'Failed to delete menu.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateMenuItem(UpdateMenuItemRequest $request, $id)
    {
        try {
            $item = $this->service->updateMenuItem($id, data: $request->validated());
            if ($item['status'] === 'Menu_not_found') {
                return ApiResponse::error(__('restaurantMenu.Menu_not_found'), [], 200);
            }
            return ApiResponse::success(
                __('restaurantMenu.data_fetched_successfully'),
                new MenuItemResource($item),
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Menu Item could not be updated.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
}
