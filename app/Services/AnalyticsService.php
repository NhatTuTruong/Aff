<?php

namespace App\Services;

use App\Models\BlockedIp;
use App\Models\Campaign;
use App\Models\PageView;
use App\Models\Click;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AnalyticsService
{
    /**
     * Track a page view (skipped if IP is blocked)
     */
    public function trackPageView(Campaign $campaign, Request $request): ?PageView
    {
        $ip = $request->ip();
        $userId = $campaign->brand?->user_id;
        if (BlockedIp::isBlocked($ip, $userId)) {
            return null;
        }

        $sessionId = Session::getId();
        $userAgent = $request->userAgent();
        
        // Get device info
        $deviceType = PageView::getDeviceType($userAgent);
        $browser = PageView::getBrowser($userAgent);
        $os = PageView::getOS($userAgent);
        
        // Get location (simplified - in production, use a geolocation service)
        $country = $this->getCountryFromIP($ip);
        $city = null; // Would need a geolocation service for this
        
        return PageView::create([
            'campaign_id' => $campaign->id,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'referer' => $request->header('referer'),
            'session_id' => $sessionId,
            'device_type' => $deviceType,
            'browser' => $browser,
            'os' => $os,
            'country' => $country,
            'city' => $city,
            'is_bounce' => true, // Will be updated if user stays
        ]);
    }

    /**
     * Track a click (skipped if IP is blocked)
     */
    public function trackClick(Campaign $campaign, Request $request): ?Click
    {
        $ip = $request->ip();
        $userId = $campaign->brand?->user_id;
        if (BlockedIp::isBlocked($ip, $userId)) {
            return null;
        }

        $userAgent = $request->userAgent();
        
        // Get device info
        $deviceType = PageView::getDeviceType($userAgent);
        $browser = PageView::getBrowser($userAgent);
        $os = PageView::getOS($userAgent);
        
        // Get location
        $country = $this->getCountryFromIP($ip);
        $city = null;
        
        return Click::create([
            'campaign_id' => $campaign->id,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'referer' => $request->header('referer'),
            'sub_id' => $request->get('sub_id'),
            'device_type' => $deviceType,
            'browser' => $browser,
            'os' => $os,
            'country' => $country,
            'city' => $city,
        ]);
    }

    private function getCountryFromIP(string $ip): ?string
    {
        return app(GeoIpService::class)->getCountry($ip);
    }

    /**
     * Calculate CTR (Click Through Rate)
     */
    public function calculateCTR(Campaign $campaign, $startDate = null, $endDate = null): float
    {
        $viewsQuery = $campaign->pageViews();
        $clicksQuery = $campaign->clicks();
        
        if ($startDate) {
            $viewsQuery->whereDate('created_at', '>=', $startDate);
            $clicksQuery->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $viewsQuery->whereDate('created_at', '<=', $endDate);
            $clicksQuery->whereDate('created_at', '<=', $endDate);
        }
        
        $views = $viewsQuery->count();
        $clicks = $clicksQuery->count();
        
        if ($views === 0) {
            return 0;
        }
        
        return round(($clicks / $views) * 100, 2);
    }

    /**
     * Calculate unique visitors
     */
    public function getUniqueVisitors(Campaign $campaign, $startDate = null, $endDate = null): int
    {
        $query = $campaign->pageViews()
            ->selectRaw('COUNT(DISTINCT CONCAT(ip, "-", COALESCE(session_id, ""))) as unique_visitors');
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        return $query->value('unique_visitors') ?? 0;
    }

    /**
     * Calculate bounce rate
     */
    public function calculateBounceRate(Campaign $campaign, $startDate = null, $endDate = null): float
    {
        $query = $campaign->pageViews();
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        $totalViews = $query->count();
        $bounces = $query->where('is_bounce', true)->count();
        
        if ($totalViews === 0) {
            return 0;
        }
        
        return round(($bounces / $totalViews) * 100, 2);
    }

    /**
     * Calculate average time on page
     */
    public function getAverageTimeOnPage(Campaign $campaign, $startDate = null, $endDate = null): float
    {
        $query = $campaign->pageViews()
            ->whereNotNull('time_on_page');
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        return round($query->avg('time_on_page') ?? 0, 2);
    }
}

