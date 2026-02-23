<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DealsController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q');

        $deals = Coupon::query()
            ->with(['campaign.brand'])
            ->whereNull('deleted_at')
            ->whereHas('campaign', fn ($q) => $q
                ->when(app()->environment('production'), fn ($q2) => $q2->where('status', 'active'))
            )
            ->whereHas('campaign.brand', function ($q) {
                $q->where('approved', true)
                    ->whereNull('deleted_at')
                    ->whereExists(function ($sub) {
                        $sub->selectRaw(1)
                            ->from('users')
                            ->whereColumn('users.id', 'brands.user_id');
                    });
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->when($query, function ($q) use ($query) {
                $q->where(function ($qq) use ($query) {
                    $qq->where('offer', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhereHas('campaign', fn ($c) => $c->where('title', 'like', "%{$query}%"))
                        ->orWhereHas('campaign.brand', fn ($b) => $b->where('name', 'like', "%{$query}%"));
                });
            })
            ->orderByRaw('
                (SELECT COUNT(*) FROM clicks WHERE clicks.campaign_id = coupons.campaign_id AND clicks.deleted_at IS NULL) +
                (SELECT COUNT(*) FROM page_views WHERE page_views.campaign_id = coupons.campaign_id) DESC,
                coupons.created_at DESC
            ')
            ->paginate(12);

        return view('deals.index', [
            'deals' => $deals,
            'searchQuery' => $query,
        ]);
    }
}

