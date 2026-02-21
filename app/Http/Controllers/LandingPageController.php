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
        // Tìm campaign theo slug đầy đủ (user_code/slug) - không phụ thuộc user để tránh 404 khi user bị xóa
        $fullSlug = "{$userCode}/{$slug}";
        $campaign = Campaign::where('slug', $fullSlug)
            ->whereHas('brand')
            ->with(['assets', 'brand', 'couponItems'])
            ->firstOrFail();

        // Only show active campaigns in production, allow all in development
        if (app()->environment('production') && $campaign->status !== 'active') {
            abort(404);
        }

        // Track page view
        $pageView = $this->analyticsService->trackPageView($campaign, $request);

        // Xác định template dựa trên type và template field
        $template = $campaign->template ?? 'template1';
        
        // Nếu type = key nhưng template chưa được set, dùng template_key
        if ($campaign->type === 'key' && !str_starts_with($template, 'template_key')) {
            $template = 'template_key';
        }
        
        // Nếu type = coupon nhưng template không phải template1, dùng template1
        if (($campaign->type ?? 'coupon') === 'coupon' && $template !== 'template1') {
            $template = 'template1';
        }
        
        // Check if template exists, fallback to default
        if (!view()->exists("landing.{$template}")) {
            $template = ($campaign->type ?? 'coupon') === 'key' ? 'template_key' : 'template1';
            if (!view()->exists("landing.{$template}")) {
                $template = 'template1';
            }
        }
        
        return view("landing.{$template}", compact('campaign', 'pageView'));
    }
}
