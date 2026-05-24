<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('notifications:send-scheduled')
    ->everyMinute();
    // ->withoutOverlapping()
    // ->runInBackground()
    // ->onOneServer()
    // ->appendOutputTo(storage_path('logs/scheduled_notifications.log'));
