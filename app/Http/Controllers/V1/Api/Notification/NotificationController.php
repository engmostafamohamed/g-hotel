<?php

namespace App\Http\Controllers\V1\Api\Notification;

use App\Http\Requests\V1\Api\Notification\SendNotificationRequest;
use App\Http\Resources\V1\Api\Notification\NotificationResource;
use App\Services\V1\Api\Notification\NotificationService;
use App\DataTransferObjects\V1\Api\Notification\NotificationDTO;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Helpers\ApiResponse;
class NotificationController extends Controller
{
    public function __construct(private NotificationService $service) {}

    public function send(SendNotificationRequest $request)
    {
        $result=$this->service->sendNotification(NotificationDTO::fromRequest($request));
        if (empty($result)) {
            return ApiResponse::error(
                __('notification.no_guest_device_found'),
                [],
                200
            );
        }
        return ApiResponse::success(
        __('notification.notification_saved_successfully'),
        [],
        200);
    }

    public function index()
    {
        $notifications = Notification::latest()->paginate(10);

        return ApiResponse::success(
        __('notification.notifications_fetched_successfully'),
        NotificationResource::collection($notifications),
        200);
    }
}

