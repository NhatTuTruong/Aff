<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class ClickTrackingController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function legacyOutRedirect(string $userCode, string $slug, Request $request)
    {
        $campaign = Campaign::where('slug', "{$userCode}/{$slug}")->first();
        if (! $campaign) {
            $campaign = Campaign::where('slug', $slug)->first();
            if ($campaign && (string) optional($campaign->brand?->user)->code !== (string) $userCode) {
                abort(404);
            }
        }
        if (! $campaign) {
            abort(404);
        }

        $url = route('click.redirect', ['slug' => $campaign->slug]);
        if ($request->getQueryString()) {
            $url .= '?' . $request->getQueryString();
        }

        return redirect()->to($url, 301);
    }

    public function redirect(string $slug, Request $request)
    {
        $campaign = Campaign::where('slug', $slug)
            ->whereHas('brand')
            ->firstOrFail();
        
        // Only restrict to active in production
        if (app()->environment('production') && $campaign->status !== 'active') {
            abort(404);
        }

        // Nếu không có affiliate_url thì không nên redirect mù mờ
        if (empty($campaign->affiliate_url)) {
            // Không lộ chi tiết lý do ra ngoài
            abort(404);
        }

        // Track the click with analytics
        $this->analyticsService->trackClick($campaign, $request);

        // Build affiliate URL with tracking
        $affiliateUrl = $campaign->affiliate_url;
        
        // Add sub_id if provided
        if ($request->get('sub_id')) {
            $separator = strpos($affiliateUrl, '?') !== false ? '&' : '?';
            $affiliateUrl .= $separator . 'sub_id=' . urlencode($request->get('sub_id'));
        }

        // Redirect to affiliate URL
        return redirect($affiliateUrl);
    }
}
