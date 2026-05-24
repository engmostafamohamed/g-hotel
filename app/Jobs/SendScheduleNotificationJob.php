<?php

namespace App\Jobs;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\V1\Api\Notification\NotificationService;
use Illuminate\Foundation\Bus\Dispatchable;

class SendScheduleNotificationJob implements ShouldQueue
{
    use Dispatchable,Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected int $notificationId;
    public function __construct(Notification $notification )
    {
        $this->notificationId = $notification->id;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $service): void
    {
        $now=Carbon::now();
        // Fetch the notification from the database
        $dueNotification = Notification::whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->whereNull('sent_at')
            ->get();
        foreach($dueNotification as $notification){
            $tokens=$notification->to_all_guest
                ? $service->getTokensForAllGuests()
                : $service->getTokensForGuests([$notification->guest_id]);

            if (empty($tokens)) {
                continue;
            }

            $service->send(
                tokens: array_column($tokens, 'device_token'),
                title: is_array($notification->title)
                    ? ($notification->title['en'] ?? reset($notification->title))
                    : $notification->title,
                message: is_array($notification->message)
                    ? ($notification->message['en'] ?? reset($notification->message))
                    : $notification->message,
                data: $notification->data ?? [],
                guestId: $notification->guest_id,
                type: $notification->type,
                sendToAllGuest: $notification->to_all_guest
            );

            $notification->update(['sent_at' => $now]);
        }
    }
}
