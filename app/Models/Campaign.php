<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'import_id',
        'slug',
        'status',
        'type',
        'title',
        'subtitle',
        'intro',
        'benefits',
        'cta_text',
        'affiliate_url',
        'link_network',
        'email',
        'coupon_code',
        'coupon_enabled',
        'template',
        'logo',
        'cover_image',
        'product_images',
        'background_image',
        'key_product_images',
    ];

    protected $casts = [
        'benefits' => 'array',
        'coupon_enabled' => 'boolean',
        'product_images' => 'array',
        'key_product_images' => 'array',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public function couponItems(): HasMany
    {
        return $this->hasMany(Coupon::class)
            ->orderByRaw('CASE WHEN sort_order IS NULL THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
    }

    public function customerLeads(): HasMany
    {
        return $this->hasMany(CustomerLead::class);
    }

    /** Tránh lỗi Array to string khi slug trong DB bị lưu sai hoặc form gửi array. */
    public function getSlugAttribute($value): ?string
    {
        if (is_array($value)) {
            return implode('/', $value);
        }
        return $value === null ? null : (string) $value;
    }

    public function getHeroImageAttribute()
    {
        return $this->assets()->where('type', 'hero')->first();
    }

    public function getProductImageAttribute()
    {
        return $this->assets()->where('type', 'product')->first();
    }

    public function getLifestyleImageAttribute()
    {
        return $this->assets()->where('type', 'lifestyle')->first();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($campaign) {
            $campaign->slug = static::normalizeCampaignSlug(
                $campaign->slug,
                $campaign->title ?? '',
                null
            );
        });

        static::updating(function ($campaign) {
            $raw = is_array($campaign->slug) ? implode('/', $campaign->slug) : (string) $campaign->slug;
            $segments = array_values(array_filter(explode('/', $raw)));
            $needsNormalize = $campaign->isDirty('slug')
                || count($segments) !== 1
                || str_contains($raw, '/');
            if ($needsNormalize) {
                $campaign->slug = static::normalizeCampaignSlug(
                    $campaign->slug,
                    $campaign->title ?? '',
                    $campaign->id
                );
            }
        });
    }

    /**
     * Slug một segment (URL /store/{slug}), duy nhất giữa các chiến dịch đang hoạt động (deleted_at null).
     * Hỗ trợ nhập cũ dạng {userCode}/{part}: chỉ giữ phần cuối làm slug.
     *
     * @param  string|array|null  $slug
     */
    public static function normalizeCampaignSlug(mixed $slug, string $title, ?int $ignoreCampaignId): string
    {
        if (is_array($slug)) {
            $slug = implode('/', $slug);
        }
        $slug = trim((string) $slug);
        $segments = array_values(array_filter(explode('/', $slug)));

        if (count($segments) === 0) {
            $base = Str::slug($title ?: 'campaign');
        } elseif (count($segments) === 1) {
            $base = Str::slug($segments[0]) ?: Str::slug($title ?: 'campaign');
        } else {
            $base = Str::slug(end($segments)) ?: Str::slug($title ?: 'campaign');
        }

        if ($base === '') {
            $base = Str::slug($title ?: 'campaign') ?: 'campaign';
        }

        return static::ensureUniqueCampaignSlug($base, $ignoreCampaignId);
    }

    public static function ensureUniqueCampaignSlug(string $base, ?int $ignoreCampaignId = null): string
    {
        $base = Str::slug($base) ?: 'campaign';
        $candidate = $base;
        $n = 2;

        $conflicts = static::query()
            ->where('slug', $candidate)
            ->whereNull('deleted_at')
            ->when($ignoreCampaignId !== null, fn ($q) => $q->where('id', '!=', $ignoreCampaignId));

        while ($conflicts->exists()) {
            $candidate = $base . '-' . $n;
            $n++;
            $conflicts = static::query()
                ->where('slug', $candidate)
                ->whereNull('deleted_at')
                ->when($ignoreCampaignId !== null, fn ($q) => $q->where('id', '!=', $ignoreCampaignId));
        }

        return $candidate;
    }
}
