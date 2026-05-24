<?php

namespace App\Services\V1\Api\Notification;

use Illuminate\Support\Facades\Http;
use App\Http\Repository\V1\Api\Notification\NotificationServiceRepository;
use App\Models\Notification;
use App\Models\GuestDeviceToken;
use App\DataTransferObjects\V1\Api\Notification\NotificationDTO;
use App\Jobs\SendScheduleNotificationJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function __construct(
        protected NotificationServiceRepository $repository
    ) {}

    public function sendNotification(NotificationDTO $dto)
    {
        $sendToAllGuest = empty($dto->guestIds);
        $tokens = $dto->guestIds
            ? $this->getTokensForGuests($dto->guestIds)
            : $this->getTokensForAllGuests();

        //if not have any token
        if (empty($tokens)) {
            return [];
        }
        // Handle scheduled notifications
        if ($dto->scheduledTimes && count($dto->scheduledTimes) > 0) {
            $notifications = [];

            foreach ($dto->scheduledTimes as $scheduledTime) {
                foreach($tokens as $tokenData){
                    $notification = $this->repository->create([
                        'guest_id' =>  $sendToAllGuest ? null:$tokenData['guest_id'],
                        'to_all_guest' => $sendToAllGuest,
                        'title'     => $dto->title,
                        'message'   => $dto->message,
                        'type'      => $dto->type,
                        'data'      => $dto->data,
                        'scheduled_at' => $scheduledTime,
                        'notifiable_type' => null,
                        'notifiable_id'   => null,
                    ]);

                    SendScheduleNotificationJob::dispatch($notification)
                        ->delay(Carbon::parse($scheduledTime));

                    $notifications[] = $notification;
                }
            }

            return $notifications;
        }

        // Send notification immediately
        return $this->send(
            tokens: $tokens,
            title: is_array($dto->title) ? $dto->title['en'] ?? reset($dto->title) : $dto->title,
            message: is_array($dto->message) ? $dto->message['en'] ?? reset($dto->message) : $dto->message,
            data: $dto->data ?? [],
            guestId: $dto->guestIds ? $dto->guestIds[0] : null,
            type: $dto->type
        );
    }

    public function send(
        array $tokens,
        string $title,
        string $message,
        array $data = [],
        ?int $guestId = null,
        ?string $type = null,
        bool $sendToAllGuest = false
    ) {
        // Send push notification to all tokens
        foreach ($tokens as $token) {
            try {
                $this->sendPushNotification($token, $title, $message, $data);
            } catch (\Exception $e) {
                Log::error('Failed to send push notification', [
                    'token' => $token,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Store notification in database
        // return $this->repository->create([
        //     'guest_id' => $guestId,
        //     'to_all_guest' => $sendToAllGuest,
        //     'title'     => $title,
        //     'message'   => $message,
        //     'type'      => $type,
        //     'data'      => $data,
        //     'notifiable_type' => null,
        //     'notifiable_id'   => null,
        //     'scheduled_at'    => null,
        // ]);
    }

    protected function sendPushNotification(
        string $token,
        string $title,
        string $message,
        array $data = []
    ): void {
        $fcmUrl    = env('FCM_URL', 'https://fcm.googleapis.com/fcm/send');
        $serverKey = env('FCM_SERVER_KEY');

        if (!$serverKey) {
            Log::warning('FCM_SERVER_KEY not configured');
            return;
        }

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post($fcmUrl, [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body'  => $message,
                'sound' => 'default',
            ],
            'data' => $data,
        ]);

        if ($response->failed()) {
            Log::error('FCM push notification failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }
    }

    public function getTokensForGuests(array $guestIds): array
    {
        return GuestDeviceToken::whereIn('guest_id', $guestIds)
            ->select('guest_id','device_token')
            ->get()
            ->toArray();
    }

    public function getTokensForAllGuests(): array
    {
        return GuestDeviceToken::select('guest_id','device_token')->get()->toArray();
    }
}
