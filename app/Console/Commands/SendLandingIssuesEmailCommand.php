<?php

namespace App\Console\Commands;

use App\Mail\LandingIssueMail;
use App\Models\LandingPageCheck;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendLandingIssuesEmailCommand extends Command
{
    protected $signature = 'landing:send-issues {--since=60 : Số phút gần nhất để lấy lỗi (mặc định 60)}';

    protected $description = 'Gửi email cho từng user khi landing/coupon của họ đang gặp lỗi (dựa trên LandingPageCheck).';

    public function handle(): int
    {
        $minutes = max(1, (int) $this->option('since'));
        $since = Carbon::now()->subMinutes($minutes);

        $this->info("Đang gửi email lỗi landing/coupon cho các bản ghi từ {$since->toDateTimeString()} trở đi...");

        $checks = LandingPageCheck::query()
            ->whereNotNull('user_id')
            ->where('status_code', '!=', 200)
            ->where('checked_at', '>=', $since)
            ->with('campaign')
            ->get()
            ->groupBy('user_id');

        if ($checks->isEmpty()) {
            $this->info('Không có lỗi landing/coupon mới trong khoảng thời gian này.');
            return self::SUCCESS;
        }

        foreach ($checks as $userId => $rows) {
            $user = User::find($userId);
            if (! $user || ! $user->email) {
                continue;
            }

            $issues = [];
            foreach ($rows as $row) {
                $issues[] = [
                    'campaign' => $row->campaign?->title,
                    'url_path' => $row->url_path,
                    'full_url' => url($row->url_path),
                    'status_code' => $row->status_code,
                    'error' => $row->error,
                    'checked_at' => $row->checked_at,
                ];
            }

            if (empty($issues)) {
                continue;
            }

            Mail::to($user->email)->send(new LandingIssueMail($user, $issues));
            $this->info("Đã gửi email lỗi landing cho user #{$userId} ({$user->email}) với " . count($issues) . ' bản ghi.');
        }

        return self::SUCCESS;
    }
}

