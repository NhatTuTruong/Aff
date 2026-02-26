<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('notifications:check-alerts')->everyThirtyMinutes();
        $schedule->command('health:check-landing --only-errors')->hourly();
        // Báo cáo hiệu suất chiến dịch cho từng user (2 lần/ngày: 8h sáng, 8h tối)
        $schedule->command('reports:send-campaign-daily')->twiceDaily(8, 20);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        if (file_exists(base_path('routes/console.php'))) {
            require base_path('routes/console.php');
        }
    }
}

