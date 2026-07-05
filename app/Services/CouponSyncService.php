<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignPostSync;
use App\Support\AutoPostSettings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CouponSyncService
{
    /**
     * @param  Collection<int, Campaign>|array<int, Campaign>  $campaigns
     * @return array{success: bool, message: string, sent: int, skipped: int, skipped_domains: list<string>}
     */
    public function syncCampaigns(Collection|array $campaigns): array
    {
        if (! AutoPostSettings::isConfigured()) {
            return [
                'success' => false,
                'message' => 'Chưa cấu hình API URL hoặc Bearer Token trong Cài đặt hệ thống → Tự động đăng bài.',
                'sent' => 0,
                'skipped' => 0,
                'skipped_domains' => [],
            ];
        }

        $platforms = AutoPostSettings::platforms();
        if ($platforms === []) {
            return [
                'success' => false,
                'message' => 'Chưa chọn nền tảng đăng bài trong Cài đặt hệ thống.',
                'sent' => 0,
                'skipped' => 0,
                'skipped_domains' => [],
            ];
        }

        $type = AutoPostSettings::type();
        $allowRepeat = AutoPostSettings::allowRepeat();
        $postedDomains = $allowRepeat ? [] : CampaignPostSync::postedDomains();

        $items = [];
        $skippedDomains = [];
        $campaignMap = [];
        $seenInBatch = [];

        $collection = $campaigns instanceof Collection ? $campaigns : collect($campaigns);
        $collection->loadMissing(['brand', 'couponItems']);

        foreach ($collection as $campaign) {
            $item = $this->buildItem($campaign, $type);
            if ($item === null) {
                continue;
            }

            $domainKey = strtolower($item['domain']);

            if (isset($seenInBatch[$domainKey])) {
                continue;
            }

            if (! $allowRepeat && in_array($domainKey, $postedDomains, true)) {
                $skippedDomains[] = $item['domain'];
                continue;
            }

            $seenInBatch[$domainKey] = true;
            $items[] = $item;
            $campaignMap[$domainKey] = $campaign;
        }

        $skippedCount = count($skippedDomains);

        if ($items === []) {
            $message = $skippedCount > 0
                ? "Không có chiến dịch nào để đăng. {$skippedCount} domain đã đăng trước đó bị bỏ qua."
                : 'Không có chiến dịch hợp lệ để đăng (thiếu domain hoặc affiliate URL).';

            return [
                'success' => false,
                'message' => $message,
                'sent' => 0,
                'skipped' => $skippedCount,
                'skipped_domains' => array_values(array_unique($skippedDomains)),
            ];
        }

        $payload = [
            'platforms' => $platforms,
            'items' => $items,
        ];

        try {
            $response = Http::timeout(120)
                ->acceptJson()
                ->asJson()
                ->withToken(AutoPostSettings::bearerToken() ?? '')
                ->post(AutoPostSettings::apiUrl(), $payload);

            if (! $response->successful()) {
                Log::warning('Coupon sync API failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'API trả về lỗi HTTP ' . $response->status() . ': ' . $response->body(),
                    'sent' => 0,
                    'skipped' => $skippedCount,
                    'skipped_domains' => array_values(array_unique($skippedDomains)),
                ];
            }

            $now = now();
            foreach ($items as $item) {
                $domainKey = strtolower($item['domain']);
                $campaign = $campaignMap[$domainKey] ?? null;

                CampaignPostSync::create([
                    'domain' => $item['domain'],
                    'campaign_id' => $campaign?->id,
                    'platforms' => $platforms,
                    'type' => $type,
                    'response_status' => $response->status(),
                    'posted_at' => $now,
                ]);
            }

            $sentCount = count($items);
            $message = "Đã gửi {$sentCount} chiến dịch đến API đăng bài.";
            if ($skippedCount > 0) {
                $message .= " Bỏ qua {$skippedCount} domain đã đăng trước đó.";
            }

            return [
                'success' => true,
                'message' => $message,
                'sent' => $sentCount,
                'skipped' => $skippedCount,
                'skipped_domains' => array_values(array_unique($skippedDomains)),
            ];
        } catch (\Throwable $e) {
            Log::error('Coupon sync API exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Không thể gọi API: ' . $e->getMessage(),
                'sent' => 0,
                'skipped' => $skippedCount,
                'skipped_domains' => array_values(array_unique($skippedDomains)),
            ];
        }
    }

    /**
     * @return array{domain: string, aff_link: string, coupon_codes: list<string>, type: string}|null
     */
    protected function buildItem(Campaign $campaign, string $type): ?array
    {
        $domain = trim((string) ($campaign->brand?->domain ?? ''));
        $affLink = trim((string) ($campaign->affiliate_url ?? ''));

        if ($domain === '' || $affLink === '') {
            return null;
        }

        $codes = $campaign->couponItems
            ->pluck('code')
            ->map(fn ($c) => trim((string) $c))
            ->filter()
            ->values()
            ->all();

        if ($campaign->coupon_code) {
            $legacy = trim((string) $campaign->coupon_code);
            if ($legacy !== '' && ! in_array($legacy, $codes, true)) {
                array_unshift($codes, $legacy);
            }
        }

        return [
            'domain' => $domain,
            'aff_link' => $affLink,
            'coupon_codes' => array_values(array_unique($codes)),
            'type' => $type,
        ];
    }
}
