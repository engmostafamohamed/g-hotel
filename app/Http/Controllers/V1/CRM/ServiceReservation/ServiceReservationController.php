<?php

namespace App\Http\Controllers\V1\CRM\ServiceReservation;

use App\DataTransferObjects\V1\CRM\ServiceReservation\StoreServiceReservationDTO;
use App\DataTransferObjects\V1\CRM\ServiceReservation\UpdateServiceReservationDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\ServiceReservation\StoreServiceReservationRequest;
use App\Http\Requests\V1\CRM\ServiceReservation\UpdateServiceReservationRequest;
use App\Http\Resources\V1\CRM\ServiceReservation\PaginatedGuestReservationsResource;
use App\Http\Resources\V1\CRM\ServiceReservation\ServiceReservationResource;
use App\Http\Resources\V1\CRM\ServiceReservation\PaginatedServiceReservationResource;
use App\Services\V1\CRM\ServiceReservation\ServiceReservationService;
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

    public function index(Request $request)
    {
        try {
            $reservations = $this->service->list($request);

            return ApiResponse::success(
                __('serviceReservation.fetched'),
                new PaginatedServiceReservationResource($reservations),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(__('serviceReservation.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $reservation = $this->service->find($id);

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

    public function getGuestsByServiceCategory(int $categoryId, Request $request)
    {
        try {
            $guests = $this->service->getGuestsByServiceCategory($categoryId, $request);

            return ApiResponse::success(
                __('serviceReservation.guests_fetched'),
                new PaginatedGuestReservationsResource($guests),
                // $guests,
                200
            );
        } catch (AuthorizationException $e) {
            return ApiResponse::error(__('serviceReservation.unauthorized_hotel_filter'), [], 403);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('serviceReservation.service_category_not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('serviceReservation.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }
    
}