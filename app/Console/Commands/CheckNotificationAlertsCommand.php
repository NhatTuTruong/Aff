<?php

namespace App\Console\Commands;

use App\Services\NotificationAlertService;
use Illuminate\Console\Command;

class CheckNotificationAlertsCommand extends Command
{
    protected $signature = 'notifications:check-alerts';

    protected $description = 'Thông báo: ngưỡng click/ngày/chiến dịch, click bất thường, sự cố hệ thống (landing)';

    public function handle(NotificationAlertService $service): int
    {
        $this->info('Đang kiểm tra các cảnh báo...');

        try {
            $service->checkAndSendAlerts();
            $this->info('✓ Đã kiểm tra: Click từ người trong ngày/chiến dịch, Click bất thường, Landing page');
        } catch (\Throwable $e) {
            $this->error('Lỗi: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
