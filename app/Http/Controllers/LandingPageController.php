<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function show($slug)
    {
        // Allow viewing draft campaigns for testing (in development)
        // In production, you may want to restrict to 'active' only
        $campaign = Campaign::where('slug', $slug)
            ->with(['assets', 'brand', 'couponItems'])
            ->firstOrFail();

        // Only show active campaigns in production, allow all in development
        if (app()->environment('production') && $campaign->status !== 'active') {
            abort(404);
        }

        $template = $campaign->template ?? 'default';
        
        // Check if template exists, fallback to default
        if (!view()->exists("landing.{$template}")) {
            $template = 'default';
        }
        
        return view("landing.{$template}", compact('campaign'));
    }
}
