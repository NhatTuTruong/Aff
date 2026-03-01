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
        return $this->hasMany(Coupon::class);
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
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
                $campaign->brand_id
            );
        });

        static::updating(function ($campaign) {
            $slug = $campaign->slug;
            $segments = array_filter(explode('/', is_array($slug) ? implode('/', $slug) : (string) $slug));
            if ($campaign->isDirty('slug') || $campaign->isDirty('title') || count($segments) > 2) {
                $campaign->slug = static::normalizeCampaignSlug(
                    $campaign->slug,
                    $campaign->title ?? '',
                    $campaign->brand_id
                );
            }
        });
    }

    /**
     * Slug luôn đúng format: {userCode}/{slugPart} (đúng 2 phần, slugPart không chứa /).
     * Tránh slug dạng 21419/55628/black-friday gây 404 và lỗi khi edit.
     *
     * @param  string|array|null  $slug
     */
    public static function normalizeCampaignSlug(mixed $slug, string $title, $brandId): string
    {
        $userCode = '00000';
        if ($brandId) {
            try {
                $brand = \App\Models\Brand::withoutEvents(fn () => \App\Models\Brand::find($brandId));
                if ($brand && $brand->user_id) {
                    $user = \App\Models\User::withoutEvents(fn () => \App\Models\User::find($brand->user_id));
                    $userCode = $user?->code ?? '00000';
                }
            } catch (\Throwable $e) {
                // keep 00000
            }
        }

        if (is_array($slug)) {
            $slug = implode('/', $slug);
        }
        $slug = trim((string) $slug);
        $segments = array_values(array_filter(explode('/', $slug)));

        if (count($segments) === 0) {
            return $userCode . '/' . Str::slug($title ?: 'campaign');
        }
        if (count($segments) === 1) {
            return $userCode . '/' . Str::slug($segments[0]);
        }
        // 2+ segments: luôn dùng userCode của brand và phần slug là 1 segment (phần cuối)
        $slugPart = Str::slug(end($segments)) ?: Str::slug($title ?: 'campaign');
        return $userCode . '/' . $slugPart;
    }
}
