<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notifications to guests at their specified time.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new SendScheduledNotifications());
        $this->info('Scheduled notifications have been processed.');
    }
}
