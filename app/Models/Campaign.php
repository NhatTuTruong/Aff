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
    ];

    protected $casts = [
        'benefits' => 'array',
        'coupon_enabled' => 'boolean',
        'product_images' => 'array',
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
            $userCode = '00000';
            if ($campaign->brand_id) {
                try {
                    $brand = $campaign->brand ?? \App\Models\Brand::withoutEvents(function () use ($campaign) {
                        return \App\Models\Brand::find($campaign->brand_id);
                    });
                    if ($brand && $brand->user_id) {
                        $user = \App\Models\User::withoutEvents(function () use ($brand) {
                            return \App\Models\User::find($brand->user_id);
                        });
                        $userCode = $user?->code ?? '00000';
                    }
                } catch (\Exception $e) {
                    $userCode = '00000';
                }
            }
            
            if (empty($campaign->slug)) {
                $baseSlug = Str::slug($campaign->title);
                $campaign->slug = "{$userCode}/{$baseSlug}";
            } elseif (!str_starts_with($campaign->slug, $userCode . '/')) {
                // Nếu slug đã có nhưng không có user_code prefix, thêm vào
                $campaign->slug = "{$userCode}/{$campaign->slug}";
            }
        });

        static::updating(function ($campaign) {
            $userCode = '00000';
            if ($campaign->brand_id) {
                try {
                    $brand = $campaign->brand ?? \App\Models\Brand::withoutEvents(function () use ($campaign) {
                        return \App\Models\Brand::find($campaign->brand_id);
                    });
                    if ($brand && $brand->user_id) {
                        $user = \App\Models\User::withoutEvents(function () use ($brand) {
                            return \App\Models\User::find($brand->user_id);
                        });
                        $userCode = $user?->code ?? '00000';
                    }
                } catch (\Exception $e) {
                    $userCode = '00000';
                }
            }
            
            // Đảm bảo slug luôn có user_code prefix
            if ($campaign->isDirty('slug') && !str_starts_with($campaign->slug, $userCode . '/')) {
                $campaign->slug = "{$userCode}/{$campaign->slug}";
            }
            
            // Nếu title thay đổi và slug chưa có user_code prefix, cập nhật lại
            if ($campaign->isDirty('title') && !$campaign->isDirty('slug')) {
                $baseSlug = Str::slug($campaign->title);
                if (!str_starts_with($campaign->slug, $userCode . '/')) {
                    $campaign->slug = "{$userCode}/{$baseSlug}";
                } else {
                    // Giữ user_code, chỉ cập nhật phần sau
                    $campaign->slug = "{$userCode}/{$baseSlug}";
                }
            }
        });
    }
}
