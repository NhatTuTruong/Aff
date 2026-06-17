<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $campaigns = Campaign::query()
            ->with([
                'brand',
            ])
            ->withCount([
                'clicks as clicks_count' => fn ($q) => $q->where('is_bot', false)->forAdminStats(),
                'pageViews as impressions_count' => fn ($q) => $q->where('is_bot', false)->forAdminStats(),
            ])
            ->orderByDesc('clicks_count')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'data' => $campaigns,
        ]);
    }
}

