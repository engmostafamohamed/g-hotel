<?php

namespace App\Http\Controllers\V1\CRM\Service;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Service\StoreServiceAvailabilityRequest;
use App\Http\Requests\V1\CRM\Service\StoreServiceRequest;
use App\Http\Requests\V1\CRM\Service\UpdateServiceRequest;
use App\Http\Resources\V1\CRM\Service\ServiceResource;
use App\Services\V1\CRM\ServiceService;
use App\Traits\UsesHotelScope;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\V1\CRM\Service\ServiceReqest;
use App\Http\Repository\V1\CRM\Service\ServiceRepository;
class ServiceController extends Controller
{
    use UsesHotelScope;
    protected $service;

    // public function __construct(ServiceRepository $ServiceRepository)
    // {
    //     $this->service = $ServiceRepository;
    // }
    // public function showServices(Request  $request)
    // {
    //     $result= $this->service->showServicesRepository($request);
    //     if ($result['status'] === 'not_found') {
    //         return ApiResponse::error(__('service.data_not_found'), [], 401);
    //     }
    //     return ApiResponse::success(
    //         __('service.data_fetched_successfully'),
    //         $result['services'],
    //         200
    //     );
    // }
    // public function addService(ServiceReqest  $request)
    // {
    //     $result= $this->service->storeServiceRepository($request);
    //     if ($result['status'] === 'image_not_found') {
    //         return ApiResponse::error(__('service.image_not_found'), [], 422);
    //     }

    //     if ($result['status'] === 'db_error' || $result['status'] === 'error') {
    //         return ApiResponse::error(__('service.error_happend'), [], 500);
    //     }
    //     return ApiResponse::success(
    //         __('service.data_added_successfully'),
    //         [],
    //         201
    //     );
    // }

    public function __construct(protected ServiceService $serviceService)
    {
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $service = $this->serviceService->store($request->validated());

            return response()->json([
                'success' => true,
                'statusCode' => 201,
                'messge' => 'Service created successfully.',
                'data' => new ServiceResource($service)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Service could not be created.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $hotelId = $this->getHotelIdFromAuth();
            $filters = [
                'category_id' => $request->query('category_id'),
                'hotel_location_id' => $request->query('hotel_location_id'),
            ];
            $services = $this->serviceService->getAll($hotelId, $request->input('per_page', 10), $filters);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'messge' => 'Services fetched successfully.',
                'data' => ServiceResource::collection($services),
                'meta' => [
                    'current_page' => $services->currentPage(),
                    'total' => $services->total(),
                    'per_page' => $services->perPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => "Service not found.",
                'errors' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Services could not be fetched.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $hotelId = $this->getHotelIdFromAuth();
            $service = $this->serviceService->getOne($hotelId, $id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'messge' => 'Service fetched successfully.',
                'data' => new ServiceResource($service)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => "Service not found.",
                'errors' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Service could not be fetched.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function update(UpdateServiceRequest $request, int $id): JsonResponse
    {
        try {
            $service = $this->serviceService->update($request->validated(), $id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'messge' => 'Service updated successfully.',
                'data' => new ServiceResource($service)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => "Service not found.",
                'errors' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Service could not be updated.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->serviceService->delete($id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Service deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => "Service not found.",
                'errors' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public function setAvailability(StoreServiceAvailabilityRequest $request, int $id): JsonResponse
    {
        try {
            $service = $this->serviceService->setAvailability($id, $request->validated());
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'messge' => 'Service schedule set successfully.',
                'data' => new ServiceResource($service)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => "Service not found.",
                'errors' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Service could not be scheduled.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
}
