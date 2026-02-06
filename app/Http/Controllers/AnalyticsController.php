<?php

namespace App\Http\Controllers;

use App\Models\PageView;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function updatePageView(PageView $pageView, Request $request)
    {
        $request->validate([
            'time_on_page' => 'nullable|integer|min:0',
            'is_bounce' => 'nullable|boolean',
        ]);

        $pageView->update([
            'time_on_page' => $request->input('time_on_page', 0),
            'is_bounce' => $request->input('is_bounce', false),
        ]);

        return response()->json(['success' => true]);
    }
}

