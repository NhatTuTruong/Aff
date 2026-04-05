<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CustomerLead;
use App\Services\GeoIpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerLeadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'campaign_id' => ['required', 'integer', Rule::exists('campaigns', 'id')],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'source_template' => ['nullable', 'string', 'max:32'],
        ]);

        $campaign = Campaign::query()->with('brand')->findOrFail($data['campaign_id']);
        $ip = (string) $request->ip();
        $country = app(GeoIpService::class)->getCountry($ip);

        CustomerLead::query()->create([
            'campaign_id' => $campaign->id,
            'user_id' => $campaign->brand?->user_id,
            'email' => strtolower(trim($data['email'])),
            'source_template' => $data['source_template'] ?? null,
            'ip_address' => $ip,
            'country' => $country,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Email received successfully.',
        ]);
    }
}

