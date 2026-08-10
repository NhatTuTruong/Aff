<?php

namespace App\Models;

use App\Services\CouponDescriptionGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'code',
        'offer',
        'description',
        'sort_order',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Coupon $coupon) {
            if (trim((string) ($coupon->description ?? '')) !== '') {
                return;
            }

            $campaign = $coupon->relationLoaded('campaign')
                ? $coupon->campaign
                : Campaign::with('brand')->find($coupon->campaign_id);

            if (! $campaign) {
                return;
            }

            $brandName = $campaign->brand->name ?? $campaign->title ?? 'this store';
            $hasCode = trim((string) ($coupon->code ?? '')) !== '';

            $coupon->description = app(CouponDescriptionGenerator::class)->generate(
                (string) ($coupon->offer ?? ''),
                $hasCode,
                $brandName,
                ($coupon->campaign_id ?? 0) * 1000 + (int) ($coupon->sort_order ?? 0)
            );
        });
    }
}


