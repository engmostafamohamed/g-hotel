<?php

namespace App\Listeners;

use App\Events\EmployeeLoggedIn;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LoginHistory;

class LogEmployeeLogin
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
    public function handle(EmployeeLoggedIn $event): void
    {
        LoginHistory::create([
            'employee_id' => $event->employee->id,
            'login_time'    => now(),
            // 'ip_address'  => request()->ip(),
            // 'user_agent'  => request()->userAgent(),
        ]);
    }
}
