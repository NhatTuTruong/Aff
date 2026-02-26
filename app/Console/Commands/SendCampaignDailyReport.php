<?php

namespace App\Console\Commands;

use App\Mail\CampaignDailyReportMail;
use App\Models\Campaign;
use App\Models\Click;
use App\Models\PageView;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendCampaignDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run via: php artisan reports:send-campaign-daily
     */
    protected $signature = 'reports:send-campaign-daily';

    /**
     * The console command description.
     */
    protected $description = 'Gửi email báo cáo hiệu suất chiến dịch cho từng user (12 giờ gần nhất).';

    public function handle(): int
    {
        $now = Carbon::now();
        $from = $now->copy()->subHours(12);

        $this->info("Sending campaign performance reports for window {$from->toDateTimeString()} → {$now->toDateTimeString()}");

        // Lấy các user có brand/campaign
        User::query()
            ->whereNotNull('email')
            ->chunkById(50, function ($users) use ($from, $now) {
                foreach ($users as $user) {
                    $this->sendReportForUser($user, $from, $now);
                }
            });

        return self::SUCCESS;
    }

    protected function sendReportForUser(User $user, Carbon $from, Carbon $to): void
    {
        // Campaign thuộc các brand của user này
        $campaignIds = Campaign::query()
            ->whereHas('brand', function (Builder $b) use ($user) {
                $b->where('user_id', $user->id);
            })
            ->pluck('id');

        if ($campaignIds->isEmpty()) {
            return;
        }

        $clicksByCampaign = Click::query()
            ->whereIn('campaign_id', $campaignIds)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('campaign_id, COUNT(*) as clicks')
            ->groupBy('campaign_id')
            ->pluck('clicks', 'campaign_id')
            ->toArray();

        $viewsByCampaign = PageView::query()
            ->whereIn('campaign_id', $campaignIds)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('campaign_id, COUNT(*) as views')
            ->groupBy('campaign_id')
            ->pluck('views', 'campaign_id')
            ->toArray();

        $campaignIdsWithActivity = array_unique(array_merge(array_keys($clicksByCampaign), array_keys($viewsByCampaign)));

        if (empty($campaignIdsWithActivity)) {
            // Không có hoạt động trong khoảng thời gian này → không gửi mail
            return;
        }

        $campaigns = Campaign::query()
            ->with('brand')
            ->whereIn('id', $campaignIdsWithActivity)
            ->get()
            ->keyBy('id');

        $totalClicks = 0;
        $totalViews = 0;
        $rows = [];

        foreach ($campaignIdsWithActivity as $cid) {
            $campaign = $campaigns->get($cid);
            if (! $campaign) {
                continue;
            }

            $clicks = (int) ($clicksByCampaign[$cid] ?? 0);
            $views = (int) ($viewsByCampaign[$cid] ?? 0);
            $ctr = $views > 0 ? round(($clicks / $views) * 100, 2) : 0.0;

            $totalClicks += $clicks;
            $totalViews += $views;

            $rows[] = [
                'campaign' => $campaign->title,
                'brand' => $campaign->brand?->name,
                'clicks' => $clicks,
                'views' => $views,
                'ctr' => $ctr,
            ];
        }

        if ($totalClicks === 0 && $totalViews === 0) {
            // Không có hoạt động thực tế, bỏ qua
            return;
        }

        // Sắp xếp theo clicks giảm dần
        usort($rows, function (array $a, array $b) {
            return $b['clicks'] <=> $a['clicks'];
        });

        $summary = [
            'from' => $from,
            'to' => $to,
            'total_clicks' => $totalClicks,
            'total_views' => $totalViews,
            'ctr' => $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 2) : 0.0,
            'campaigns' => $rows,
        ];

        if (! $user->email) {
            return;
        }

        Mail::to($user->email)->send(new CampaignDailyReportMail($user, $summary));
    }
}

