<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Click;
use Illuminate\Http\Request;

class ClickTrackingController extends Controller
{
    public function redirect($slug, Request $request)
    {
        // Allow viewing draft campaigns for testing (in development)
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        
        // Only restrict to active in production
        if (app()->environment('production') && $campaign->status !== 'active') {
            abort(404);
        }

        // Track the click
        Click::create([
            'campaign_id' => $campaign->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'sub_id' => $request->get('sub_id'),
        ]);

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
