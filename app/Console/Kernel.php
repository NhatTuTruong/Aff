<?php

namespace App\Console;

use App\Support\AdminSettings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('notifications:check-alerts')->everyThirtyMinutes();
        $schedule->command('health:check-landing --only-errors')->hourly();
        $schedule->command('landing:send-issues')->hourly()->withoutOverlapping();
        // Báo cáo hiệu suất chiến dịch cho từng user (2 lần/ngày: 8h sáng, 8h tối)
        $schedule->command('reports:send-campaign-daily')->twiceDaily(8, 20);

        // Auto Blog theo cài đặt hệ thống: khung giờ + số bài/ngày + variant.
        $schedule->command('blogs:generate-daily --respect-daily-limit')
            ->hourly()
            ->withoutOverlapping()
            ->when(function (): bool {
                if (! (bool) AdminSettings::get('auto_blog_enabled', true)) {
                    return false;
                }

                $startHour = max(0, min(23, (int) AdminSettings::get('auto_blog_window_start_hour', 6)));
                $endHour = max(0, min(23, (int) AdminSettings::get('auto_blog_window_end_hour', 18)));
                $hour = now()->hour;

                if ($startHour <= $endHour) {
                    return $hour >= $startHour && $hour <= $endHour;
                }

                // Khung giờ qua đêm, ví dụ 22 -> 4
                return $hour >= $startHour || $hour <= $endHour;
            });
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

