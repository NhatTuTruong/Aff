<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Click;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NotificationAlertService
{
    protected const CACHE_PREFIX_CAMPAIGN_HUMAN_CLICKS_DAILY = 'notification_campaign_human_clicks_daily_';

    protected const HUMAN_CLICKS_PER_DAY_MIN = 10;

    public function checkAndSendAlerts(): void
    {
        $this->notifyCampaignsDailyHumanClicksThreshold();
        $this->checkUnusualClicks();
        $this->checkLandingPageAvailability();
    }

    /**
     * Một thông báo đơn giản cho chủ brand: trong ngày chiến dịch đạt tối thiểu N click từ người (không bot),
     * gửi tối đa một lần mỗi ngày mỗi chiến dịch. Không còn mốc 100/200/300 hay so sánh tăng/giảm với hôm qua.
     */
    protected function notifyCampaignsDailyHumanClicksThreshold(): void
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $rows = Click::query()
            ->where('is_bot', false)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->selectRaw('campaign_id, COUNT(*) as cnt')
            ->groupBy('campaign_id')
            ->havingRaw('COUNT(*) >= ?', [self::HUMAN_CLICKS_PER_DAY_MIN])
            ->get();

        foreach ($rows as $row) {
            $campaignId = (int) $row->campaign_id;
            $count = (int) $row->cnt;

            $cacheKey = self::CACHE_PREFIX_CAMPAIGN_HUMAN_CLICKS_DAILY . $campaignId . '_' . $todayStart->toDateString();
            if (Cache::has($cacheKey)) {
                continue;
            }

            $campaign = Campaign::with('brand.user')->find($campaignId);
            if (! $campaign || ! $campaign->brand?->user) {
                continue;
            }

            $user = $campaign->brand->user;

            Notification::make()
                ->title('Chiến dịch đạt ' . self::HUMAN_CLICKS_PER_DAY_MIN . '+ click từ người hôm nay')
                ->body("{$campaign->title}: {$count} click (đã loại bot).")
                ->success()
                ->icon('heroicon-o-cursor-arrow-rays')
                ->sendToDatabase($user);

            Cache::put($cacheKey, true, $todayEnd);
        }
    }

    protected function checkUnusualClicks(): void
    {
        $recent = now()->subMinutes(10);
        $suspicious = Click::query()
            ->select('ip', DB::raw('COUNT(*) as cnt'))
            ->where('created_at', '>=', $recent)
            ->where('is_bot', false)
            ->groupBy('ip')
            ->having('cnt', '>=', 20)
            ->get();

        if ($suspicious->isEmpty()) {
            return;
        }

        $users = User::where('is_admin', true)->get();
        if ($users->isEmpty()) {
            $users = User::limit(1)->get();
        }

        foreach ($suspicious as $item) {
            $ip = $item->ip;
            $cnt = $item->cnt;
            $cacheKey = 'notification_unusual_ip_' . $ip . '_' . now()->format('Y-m-d-H-i');

            if (Cache::has($cacheKey)) {
                continue;
            }

            foreach ($users as $user) {
                Notification::make()
                    ->title('Phát hiện click bất thường')
                    ->body("IP {$ip} có {$cnt} clicks trong 10 phút gần đây. Cân nhắc chặn IP.")
                    ->warning()
                    ->icon('heroicon-o-shield-exclamation')
                    ->sendToDatabase($user);
            }

            Cache::put($cacheKey, true, now()->addMinutes(15));
        }
    }

    protected function checkLandingPageAvailability(): void
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $testUrl = $baseUrl . '/';
        $cacheKey = 'notification_landing_down_' . now()->format('Y-m-d-H');

        try {
            $response = Http::timeout(5)->get($testUrl);
            if ($response->successful()) {
                return;
            }
        } catch (\Throwable $e) {
            // Site down or unreachable
        }

        if (Cache::has($cacheKey)) {
            return;
        }

        $admins = User::where('is_admin', true)->get();
        if ($admins->isEmpty()) {
            $admins = User::limit(1)->get();
        }

        foreach ($admins as $user) {
            Notification::make()
                ->title('Sự cố: Trang web có thể đang ngưng hoạt động')
                ->body("Không thể truy cập {$baseUrl}. Vui lòng kiểm tra.")
                ->danger()
                ->icon('heroicon-o-exclamation-triangle')
                ->sendToDatabase($user);

            if ($user->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(
                        new \App\Mail\SystemAlertMail(
                            'Sự cố hệ thống: Website có thể đang ngưng hoạt động',
                            "Hệ thống không thể truy cập {$baseUrl} trong lần kiểm tra gần nhất.\n\n" .
                            "Bạn nên kiểm tra lại server, domain hoặc cấu hình hosting để đảm bảo landing/coupon vẫn hoạt động bình thường."
                        )
                    );
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        Cache::put($cacheKey, true, now()->addHour());
    }
}
