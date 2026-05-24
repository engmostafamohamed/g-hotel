<?php

namespace App\Http\Controllers\V1\CRM\Feedback;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CRM\Feedback\FeedbackResource;
use App\Http\Resources\V1\CRM\Feedback\PaginatedFeedbackResource;
use App\Services\V1\CRM\Feedback\FeedbackService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class FeedbackController extends Controller
{
    public function __construct(private FeedbackService $service) {}

    public function index(Request $request)
    {
        try {
            $feedbacks = $this->service->list($request->only(['guest_id', 'hotel-id', 'service_id', 'date_from', 'date_to', 'rating']));
            return ApiResponse::success(__('feedback.fetched'), new PaginatedFeedbackResource($feedbacks), 200);
        } catch (AuthorizationException $e) {
            return ApiResponse::error(__('feedback.unauthorized_hotel_filter'), [], 403);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $feedback = $this->service->find($id);
            return ApiResponse::success(__('feedback.fetched_single'), new FeedbackResource($feedback), 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('feedback.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function getFeedbackByServiceReservation(int $serviceReservationId){
        try {
            $feedback = $this->service->getFeedbackByServiceReservation($serviceReservationId);
            return ApiResponse::success(__('feedback.fetched_single'), new FeedbackResource($feedback), 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('feedback.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }

    public function getFeedbackByBooking(int $bookingId){
        try {
            $feedback = $this->service->getFeedbackByBooking($bookingId);
            return ApiResponse::success(__('feedback.fetched_single'), new FeedbackResource($feedback), 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(__('feedback.not_found'), [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error(__('feedback.unexpected_fetch'), [$e->getMessage()], 500);
        }
    }
}
