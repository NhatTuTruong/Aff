<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q');

        $brands = Brand::query()
            ->where('approved', true)
            ->when($query, fn ($q) => $q->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%");
            }))
            ->with(['category', 'campaigns' => fn ($q) => $q->limit(1)])
            ->orderBy('name')
            ->limit(100)
            ->get();

        $hotCoupons = Coupon::query()
            ->with(['campaign.brand'])
            ->whereHas('campaign.brand', fn ($q) => $q->where('approved', true))
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        return view('home', [
            'brands' => $brands,
            'hotCoupons' => $hotCoupons,
            'searchQuery' => $query,
        ]);
    }
}
