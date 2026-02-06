<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
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
            if (empty($campaign->slug)) {
                $campaign->slug = Str::slug($campaign->title);
            }
        });
    }
}
