<?php
namespace App\DataTransferObjects\V1\Api\Notification;
use App\Http\Requests\V1\Api\Notification\SendNotificationRequest;

class NotificationDTO
{
    public function __construct(
        public array $title,
        public array $message,
        public ?string $type,
        public array $guestIds,
        public array $data = [],
        public ?array $scheduledTimes = []
    ) {}

    public static function fromRequest(SendNotificationRequest $request): self
    {
        return new self(
            title: $request->input('title',[]),
            message: $request->input('message',[]),
            type: $request->input('type'),
            guestIds: $request->input('guest_ids', []),
            data: $request->input('data', []),
            scheduledTimes: $request->input('scheduled_times', [])
        );
    }
}

