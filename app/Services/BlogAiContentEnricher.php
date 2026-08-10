<?php

namespace App\Services;

use App\Models\Campaign;

class BlogAiContentEnricher
{
    /**
     * Gỡ markdown fence AI hay trả về (```html ... ```).
     */
    public function sanitizeAiHtml(string $html): string
    {
        $html = trim($html);

        if (preg_match('/^\s*```(?:html|HTML)?\s*\R(.*)\R?\s*```\s*$/s', $html, $matches)) {
            return trim($matches[1]);
        }

        $html = preg_replace('/^\s*```(?:html|HTML)?\s*\R?/i', '', $html) ?? $html;
        $html = preg_replace('/\R?\s*```\s*$/', '', $html) ?? $html;
        $html = preg_replace('/^\s*```(?:html|HTML)\s+/i', '', $html) ?? $html;

        return trim($html);
    }

    /**
     * @param  list<string>  $couponCodes
     */
    public function appendDealsBlock(string $html, ?string $affiliateUrl, array $couponCodes): string
    {
        $html = $this->sanitizeAiHtml($html);
        $affiliateUrl = trim((string) $affiliateUrl);
        $couponCodes = array_values(array_unique(array_filter(array_map(
            static fn (string $code): string => trim($code),
            $couponCodes
        ))));

        if ($affiliateUrl === '' && $couponCodes === []) {
            return $html;
        }

        $parts = ['<h2>Current deals &amp; coupons</h2>'];

        if ($affiliateUrl !== '') {
            $escapedUrl = htmlspecialchars($affiliateUrl, ENT_QUOTES, 'UTF-8');
            $parts[] = '<p><a href="'.$escapedUrl.'" rel="nofollow sponsored" target="_blank">Shop now — get the latest offer</a></p>';
        }

        if ($couponCodes !== []) {
            $items = array_map(
                static fn (string $code): string => '<li><strong>'.htmlspecialchars($code, ENT_QUOTES, 'UTF-8').'</strong></li>',
                $couponCodes
            );
            $parts[] = '<ul>'.implode('', $items).'</ul>';
        }

        return rtrim($html)."\n".implode("\n", $parts);
    }

    /**
     * @return list<string>
     */
    public function parseCouponCodes(?string $raw): array
    {
        if ($raw === null || trim($raw) === '') {
            return [];
        }

        $lines = preg_split('/\R+/', $raw) ?: [];
        $codes = [];

        foreach ($lines as $line) {
            foreach (preg_split('/[,;]+/', $line) ?: [] as $segment) {
                $code = trim($segment);
                if ($code !== '') {
                    $codes[] = $code;
                }
            }
        }

        return $codes;
    }

    public function affiliateUrlForCampaign(Campaign $campaign): string
    {
        return route('click.redirect', ['slug' => $campaign->slug], true);
    }

    /**
     * @return list<string>
     */
    public function couponCodesFromCampaign(Campaign $campaign): array
    {
        $campaign->loadMissing('couponItems');
        $codes = [];

        foreach ($campaign->couponItems as $coupon) {
            $code = trim((string) ($coupon->code ?? ''));
            if ($code === '' || strtolower($code) === 'no') {
                continue;
            }
            $codes[] = $code;
        }

        $legacyCode = trim((string) ($campaign->coupon_code ?? ''));
        if ($legacyCode !== '' && strtolower($legacyCode) !== 'no') {
            $codes[] = $legacyCode;
        }

        return array_values(array_unique($codes));
    }

    public function appendCampaignDeals(string $html, Campaign $campaign): string
    {
        return $this->appendDealsBlock(
            $html,
            $this->affiliateUrlForCampaign($campaign),
            $this->couponCodesFromCampaign($campaign)
        );
    }
}
