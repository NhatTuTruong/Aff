<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Click;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NotificationAlertService
{
    protected const MILESTONES = [100, 200, 300];

    protected const CACHE_PREFIX_MILESTONE = 'notification_milestone_';

    protected const CACHE_PREFIX_ALERT_DAILY = 'notification_alert_click_';

    public function checkAndSendAlerts(): void
    {
        $this->checkCampaignMilestones();
        $this->checkClickAnomaly();
        $this->checkUnusualClicks();
        $this->checkLandingPageAvailability();
    }

    protected function checkCampaignMilestones(): void
    {
        $campaignIds = Click::query()
            ->select('campaign_id')
            ->selectRaw('COUNT(*) as total_clicks')
            ->groupBy('campaign_id')
            ->havingRaw('COUNT(*) >= 100')
            ->pluck('total_clicks', 'campaign_id');

        foreach ($campaignIds as $campaignId => $totalClicks) {
            $campaign = Campaign::with('brand.user')->find($campaignId);
            if (! $campaign || ! $campaign->brand?->user) {
                continue;
            }

            $user = $campaign->brand->user;

            foreach (self::MILESTONES as $milestone) {
                if ($totalClicks >= $milestone) {
                    $cacheKey = self::CACHE_PREFIX_MILESTONE . "{$campaignId}_{$milestone}";
                    if (Cache::has($cacheKey)) {
                        continue;
                    }

                    Notification::make()
                        ->title("Chiến dịch đạt mốc {$milestone} clicks")
                        ->body("{$campaign->title} đã đạt {$totalClicks} clicks.")
                        ->success()
                        ->icon('heroicon-o-trophy')
                        ->sendToDatabase($user);

                    Cache::put($cacheKey, true, now()->addDays(30));
                }
            }
        }
    }

    protected function checkClickAnomaly(): void
    {
        $todayStart = now()->startOfDay();
        $yesterdayStart = now()->subDay()->startOfDay();
        $todayEnd = now()->endOfDay();
        $yesterdayEnd = now()->subDay()->endOfDay();

        $userIds = Brand::whereNotNull('user_id')->pluck('user_id')->unique();

        foreach ($userIds as $userId) {
            $brandIds = Brand::where('user_id', $userId)->pluck('id');
            $campaignIds = Campaign::whereIn('brand_id', $brandIds)->pluck('id');

            $todayCount = Click::whereIn('campaign_id', $campaignIds)
                ->whereBetween('created_at', [$todayStart, $todayEnd])
                ->count();
            $yesterdayCount = Click::whereIn('campaign_id', $campaignIds)
                ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
                ->count();

            if ($yesterdayCount < 5) {
                continue;
            }

            $change = $yesterdayCount > 0
                ? (($todayCount - $yesterdayCount) / $yesterdayCount) * 100
                : 0;

            $cacheKey = self::CACHE_PREFIX_ALERT_DAILY . $userId . '_' . $todayStart->toDateString();
            if (Cache::has($cacheKey)) {
                continue;
            }

            $user = User::find($userId);
            if (! $user) {
                continue;
            }

            if ($change <= -50) {
                Notification::make()
                    ->title('Cảnh báo: Click giảm mạnh')
                    ->body("Hôm nay click giảm " . round(abs($change)) . "% so với hôm qua ({$todayCount} vs {$yesterdayCount}).")
                    ->warning()
                    ->icon('heroicon-o-arrow-trending-down')
                    ->sendToDatabase($user);
                Cache::put($cacheKey, true, now()->endOfDay());
            } elseif ($change >= 100 && $todayCount >= 10) {
                Notification::make()
                    ->title('Click tăng đột biến')
                    ->body("Hôm nay click tăng " . round($change) . "% so với hôm qua ({$todayCount} vs {$yesterdayCount}).")
                    ->success()
                    ->icon('heroicon-o-arrow-trending-up')
                    ->sendToDatabase($user);
                Cache::put($cacheKey, true, now()->endOfDay());
            }
        }
    }

    protected function checkUnusualClicks(): void
    {
        $recent = now()->subMinutes(10);
        $suspicious = Click::query()
            ->select('ip', DB::raw('COUNT(*) as cnt'))
            ->where('created_at', '>=', $recent)
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
            
            // Chỉ gửi 1 lần cho mỗi IP trong mỗi phút
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
            
            // Cache 15 phút để tránh spam
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
                    // Tránh làm hỏng job do lỗi gửi mail
                    report($e);
                }
            }
        }

        Cache::put($cacheKey, true, now()->addHour());
    }
}
