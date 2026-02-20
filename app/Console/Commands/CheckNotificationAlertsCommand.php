<?php

namespace App\Console\Commands;

use App\Services\NotificationAlertService;
use Illuminate\Console\Command;

class CheckNotificationAlertsCommand extends Command
{
    protected $signature = 'notifications:check-alerts';

    protected $description = 'Kiểm tra và gửi thông báo về hiệu suất, mốc chiến dịch, sự cố hệ thống';

    public function handle(NotificationAlertService $service): int
    {
        $this->info('Đang kiểm tra các cảnh báo...');

        try {
            $service->checkAndSendAlerts();
            $this->info('✓ Đã kiểm tra: Mốc chiến dịch, Click anomaly, Click bất thường, Landing page');
        } catch (\Throwable $e) {
            $this->error('Lỗi: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
