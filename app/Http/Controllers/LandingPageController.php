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

    /**
     * Chuyển URL cũ /visit/{user_code}/{segment} sang /store/{slug} (301).
     */
    public function legacyVisitRedirect(string $userCode, string $slug, Request $request)
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

        return redirect()->route('landing.show', ['slug' => $campaign->slug], 301);
    }

    /**
     * Chuyển URL cũ /visit/{slug} sang /store/{slug} (301).
     */
    public function visitSlugRedirect(string $slug)
    {
        return redirect()->route('landing.show', ['slug' => $slug], 301);
    }

    public function show(string $slug, Request $request)
    {
        $campaign = Campaign::where('slug', $slug)
            ->whereHas('brand')
            ->with(['assets', 'brand', 'couponItems'])
            ->firstOrFail();

        // Only show active campaigns in production, allow all in development
        if (app()->environment('production') && $campaign->status !== 'active') {
            abort(404);
        }

        // Track page view (skip when running internal health checks)
        $pageView = null;
        $isHealthCheck = $request->headers->has('X-Health-Check') || $request->boolean('health_check');
        if (! $isHealthCheck) {
            $pageView = $this->analyticsService->trackPageView($campaign, $request);
        }

        // Xác định template dựa trên type và template field
        $template = $campaign->template ?? 'template1';
        
        // Nếu type = key nhưng template chưa được set, dùng template_key
        if ($campaign->type === 'key' && !str_starts_with($template, 'template_key')) {
            $template = 'template_key';
        }
        
        // Cho phép template1 hoặc template2 với type coupon
        
        // Check if template exists, fallback to default
        if (!view()->exists("landing.{$template}")) {
            $template = ($campaign->type ?? 'coupon') === 'key' ? 'template_key' : 'template1';
            if (!view()->exists("landing.{$template}")) {
                $template = 'template1';
            }
        }
        
        request()->attributes->set('landing_template', $template);

        return view("landing.{$template}", compact('campaign', 'pageView'));
    }
}
