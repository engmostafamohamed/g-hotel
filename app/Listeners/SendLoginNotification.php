<?php

namespace App\Listeners;

use App\Events\GuestLoggedIn;
use App\Models\GuestDeviceToken;

class SendLoginNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GuestLoggedIn $event): void
    {
        $guest = $event->guest;
        $token = request()->header('device-token');
        if ($token) {
            $platform = $this->detectPlatform(request()->header('User-Agent'));
            GuestDeviceToken::updateOrCreate([
                'device_token' => $token,
            ], [
                'guest_id' => $guest->id,
                'platform' => $platform,
            ]);
        }
    }
    public function detectPlatform($userAgent)
    {
        $userAgent = strtolower($userAgent);
        if (strpos($userAgent, 'iphone') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'ios';
        } elseif (strpos($userAgent, 'android') !== false) {
            return 'android';
        } elseif (strpos($userAgent, 'windows') !== false || strpos($userAgent, 'macintosh') !== false || strpos($userAgent, 'linux') !== false) {
            return 'web';
        } else {
            return 'unknown';
        }
    }}
