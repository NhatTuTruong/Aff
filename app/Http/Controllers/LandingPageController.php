<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function show($userCode, $slug, Request $request)
    {
        // Tìm user theo code
        $user = \App\Models\User::where('code', $userCode)->firstOrFail();
        
        // Tìm campaign theo slug đầy đủ (user_code/slug)
        $fullSlug = "{$userCode}/{$slug}";
        $campaign = Campaign::where('slug', $fullSlug)
            ->whereHas('brand', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['assets', 'brand', 'couponItems'])
            ->firstOrFail();

        // Only show active campaigns in production, allow all in development
        if (app()->environment('production') && $campaign->status !== 'active') {
            abort(404);
        }

        // Track page view
        $pageView = $this->analyticsService->trackPageView($campaign, $request);

        $template = $campaign->template ?? 'default';
        
        // Check if template exists, fallback to default
        if (!view()->exists("landing.{$template}")) {
            $template = 'default';
        }
        
        return view("landing.{$template}", compact('campaign', 'pageView'));
    }
}
