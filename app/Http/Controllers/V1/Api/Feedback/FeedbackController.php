<?php

namespace App\Http\Controllers\V1\Api\Feedback;

use App\DataTransferObjects\V1\Api\Feedback\FeedbackDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\Feedback\StoreFeedbackRequest;
use App\Http\Requests\V1\Api\Feedback\UpdateFeedbackRequest;
use App\Http\Resources\V1\Api\Feedback\FeedbackResource;
use App\Http\Resources\V1\Api\Feedback\PaginatedFeedbackResource;
use App\Services\V1\Api\Feedback\FeedbackService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class FeedbackController extends Controller
{
    public function __construct(private FeedbackService $service) {}

    public function store(StoreFeedbackRequest $request)
    {
        try {
            $feedback = $this->service->create(FeedbackDTO::fromRequest($request, auth('guest')->id()));
            return ApiResponse::success(__('feedback.created'), new FeedbackResource($feedback), 201);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_create'), [$e->getMessage()], 500);
        }
    }

    public function update(UpdateFeedbackRequest $request, int $id)
    {
        try {
            $feedback = $this->service->find($id, auth('guest')->id());
            $updated = $this->service->update($feedback, FeedbackDTO::fromRequest($request, auth('guest')->id()));
            return ApiResponse::success(__('feedback.updated'), new FeedbackResource($updated), 200);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_update'), [$e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $feedbacks = $this->service->list(auth('guest')->id(), $request->only(['service_id', 'date_from', 'date_to', 'rating']));
            return ApiResponse::success(__('feedback.fetched'), new PaginatedFeedbackResource($feedbacks), 200);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $feedback = $this->service->find($id, auth('guest')->id());
            return ApiResponse::success(__('feedback.fetched_single'), new FeedbackResource($feedback), 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('feedback.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function getFeedbackByServiceReservation(int $serviceReservationId){
        try {
            $feedback = $this->service->getFeedbackByServiceReservation($serviceReservationId, auth('guest')->id());
            return ApiResponse::success(__('feedback.fetched_single'), new FeedbackResource($feedback), 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('feedback.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function getFeedbackByBooking(int $bookingId){
        try {
            $feedback = $this->service->getFeedbackByBooking($bookingId, auth('guest')->id());
            return ApiResponse::success(__('feedback.fetched_single'), new FeedbackResource($feedback), 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('feedback.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }
}
