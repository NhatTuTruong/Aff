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

    public function redirect($slug, Request $request)
    {
        // Allow viewing draft campaigns for testing (in development)
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        
        // Only restrict to active in production
        if (app()->environment('production') && $campaign->status !== 'active') {
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
