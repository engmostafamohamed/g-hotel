<?php

namespace App\Http\Controllers\V1\CRM\HotelLocation;

use App\Http\Controllers\Controller;
use App\Http\Repository\V1\CRM\HotelLocation\HotelLocationRepository;
use App\Http\Requests\V1\CRM\HotelLocation\CreateLocationRequest;
use App\Http\Requests\V1\CRM\HotelLocation\UpdateHotelLocationRequest;
use App\Http\Resources\V1\CRM\HotelLocation\LocationResource;
use App\Http\Resources\V1\CRM\HotelLocation\PaginatedHotelLocationsResource;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class HotelLocationController extends Controller
{
    protected $repo;

    public function __construct(HotelLocationRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store(CreateLocationRequest $request): JsonResponse
    {
        try {
            $property = $this->repo->create($request->validated());

            return response()->json([
                'success' => true,
                'statusCode' => '201',
                'message' => 'Location created successfully',
                'data' => new LocationResource($property)
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Hotel Location could not be created.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }

    }
    public function index(Request $request): JsonResponse
    {
        try {
            $properties = $this->repo->getAllPaginated($request->input('per_page', 10));

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Hotel Locations fetched successfully',
                'data' => new PaginatedHotelLocationsResource($properties)
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Hotel Locations could not be fetched.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $property = $this->repo->find($id);

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Hotel Location fetched successfully',
                'data' => new LocationResource($property)
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 200,
                'message' => 'Hotel Location could not be fetched.',
                'errors' => $e->getMessage()
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Hotel Location could not be fetched.',
                'errors' => $e->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400,
                'message' => 'Hotel Location could not be fetched.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function update(UpdateHotelLocationRequest $request, int $id): JsonResponse
    {
        try {
            $hotel = $this->repo->update($request->validated(), $id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Hotel Location updated successfully',
                'data' => new LocationResource($hotel)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400,
                'message' => 'Hotel Location could not be updated.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Hotel Location deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400,
                'message' => 'Hotel Location could not be deleted.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
}
