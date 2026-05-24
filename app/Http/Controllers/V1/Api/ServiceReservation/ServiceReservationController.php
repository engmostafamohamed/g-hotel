<?php

namespace App\Http\Controllers\V1\Api\ServiceReservation;

use App\DataTransferObjects\V1\Api\ServiceReservation\StoreServiceReservationDTO;
use App\DataTransferObjects\V1\Api\ServiceReservation\UpdateServiceReservationDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\ServiceReservation\StoreServiceReservationRequest;
use App\Http\Requests\V1\Api\ServiceReservation\UpdateServiceReservationRequest;
use App\Http\Resources\V1\Api\ServiceReservation\ServiceReservationResource;
use App\Http\Resources\V1\Api\ServiceReservation\PaginatedServiceReservationResource;
use App\Services\V1\Api\ServiceReservation\ServiceReservationService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use Illuminate\Validation\ValidationException;

class ServiceReservationController extends Controller
{
    public function __construct(private ServiceReservationService $service) {}

    public function store(StoreServiceReservationRequest $request)
    {
        try {
            $reservation = $this->service->makeReservation(StoreServiceReservationDTO::fromRequest($request));

            return ApiResponse::success(
                __('serviceReservation.created'),
                new ServiceReservationResource($reservation),
                201
            );
        } catch (ValidationException $e) {
            return ApiResponse::error(__('serviceReservation.validation_failed'), [$e->getMessage()], 422);
        } catch (Throwable $e) {
            return ApiResponse::error(__('serviceReservation.unexpected_create'), [$e->getMessage()], 500);
        }
    }

    public function update(UpdateServiceReservationRequest $request, int $id)
    {
        try {
            $reservation = $this->service->update($id, UpdateServiceReservationDTO::fromRequest($request));

            return ApiResponse::success(
                __('serviceReservation.updated'),
                new ServiceReservationResource($reservation),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('serviceReservation.not_found'), [], 404);
        } catch (AuthorizationException $e) {
            return ApiResponse::error(__('serviceReservation.unauthorized'), [$e->getMessage()], 403);
        } catch (ValidationException $e) {
            return ApiResponse::error(__('serviceReservation.validation_failed'), [$e->getMessage()], 422);
        } catch (Throwable $e) {
            return ApiResponse::error(__('serviceReservation.unexpected_update'), [$e->getMessage()], 500);
        }
    }

    public function indexForGuest(Request $request)
    {
        try {
            $reservations = $this->service->listForGuest($request);

            return ApiResponse::success(
                __('serviceReservation.fetched'),
                new PaginatedServiceReservationResource($reservations),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('serviceReservation.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }
    public function showForGuest(int $id)
    {
        try {
            $reservation = $this->service->findForGuest($id);

            return ApiResponse::success(
                __('serviceReservation.fetched_single'),
                new ServiceReservationResource($reservation),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('serviceReservation.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('serviceReservation.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }
}
