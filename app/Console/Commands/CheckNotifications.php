<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckNotifications extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifications:check';

    /**
     * The console command description.
     */
    protected $description = 'Check business notifications and send emails';

    public function handle(NotificationService $notificationService): int
    {
        $notificationService->notifyExpiringContracts();
        $notificationService->notifyMissingPhotoReports();

        $this->info('Notifications checked successfully.');

        return self::SUCCESS;
    }
}
